<?php

namespace App\Jobs;

use App\ImportRowStatus;
use App\Models\Lead;
use App\Models\LeadImportRun;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class ValidateImportRowsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(
        public LeadImportRun $run
    ) {}

    public function handle(): void
    {
        $this->run->load(['leadSource', 'rows']);
        $source = $this->run->leadSource;
        $rules = $source->validation_rules ?? [];
        $required = $rules['required'] ?? ['email'];
        $emailFormat = $rules['email_format'] ?? true;
        $fieldMap = $rules['field_map'] ?? [];
        $allowedKeys = array_diff(
            (new Lead)->getFillable(),
            ['lead_source_id', 'lead_source', 'verification_status', 'quality_score', 'is_duplicate', 'duplicate_of_lead_id']
        );

        $stats = $this->run->stats ?? ['total' => 0, 'invalid' => 0, 'valid' => 0];
        $stats['invalid'] = 0;
        $stats['valid'] = 0;

        foreach ($this->run->rows as $row) {
            $raw = $row->raw_data ?? [];
            $normalized = $this->normalize($raw, $fieldMap, $allowedKeys);
            $errors = [];
            foreach ($required as $key) {
                $val = $normalized[$key] ?? null;
                if (blank($val)) {
                    $errors[] = "Missing required: {$key}";
                }
            }
            if ($emailFormat && ! empty($normalized['email']) && ! filter_var($normalized['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format';
            }
            if ($errors !== []) {
                $row->update([
                    'normalized_data' => $normalized,
                    'status' => ImportRowStatus::Invalid,
                    'validation_errors' => $errors,
                ]);
                $stats['invalid']++;
            } else {
                $row->update([
                    'normalized_data' => $normalized,
                    'status' => ImportRowStatus::Valid,
                    'validation_errors' => null,
                ]);
                $stats['valid']++;
            }
        }
        $this->run->update(['stats' => $stats]);
        DeduplicateImportRowsJob::dispatch($this->run);
    }

    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, string>  $fieldMap
     * @param  array<int, string>  $allowedKeys
     * @return array<string, mixed>
     */
    private function normalize(array $raw, array $fieldMap, array $allowedKeys): array
    {
        $out = [];
        foreach ($raw as $key => $value) {
            if (! is_scalar($value)) {
                continue;
            }
            $mapped = $fieldMap[$key] ?? $key;
            if (in_array($mapped, $allowedKeys, true)) {
                $out[$mapped] = $this->sanitizeValue($value);
            }
        }

        return $out;
    }

    /**
     * Sanitize a scalar value for safe storage (XSS prevention).
     */
    private function sanitizeValue(mixed $value): mixed
    {
        if (is_string($value)) {
            $value = strip_tags($value);

            return Str::limit($value, 65535);
        }

        return $value;
    }
}
