<?php

namespace App\Jobs;

use App\Models\LeadCollectorRun;
use App\RawLeadRecordStatus;
use App\Services\LeadCollectors\RawLeadNormalizerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class NormalizeRawLeadRecordsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public int $backoff = 30;

    public function __construct(
        public LeadCollectorRun $run
    ) {}

    public function handle(RawLeadNormalizerService $normalizer): void
    {
        Log::info('NormalizeRawLeadRecordsJob started', ['run_id' => $this->run->id]);

        $records = $this->run->rawLeadRecords()->where('processing_status', RawLeadRecordStatus::Pending)->get();
        $processed = 0;
        $failed = 0;

        foreach ($records as $record) {
            try {
                $normalized = $normalizer->normalize($record->raw_payload ?? []);
                $record->update([
                    'normalized_payload' => $normalized,
                    'processing_status' => RawLeadRecordStatus::Normalized,
                    'company_name' => $normalized['company_name'] ?? $record->company_name,
                    'website' => $normalized['website'] ?? $record->website,
                    'email' => $normalized['email'] ?? $record->email,
                    'phone' => $normalized['phone'] ?? $record->phone,
                    'address' => $normalized['address'] ?? $record->address,
                    'country' => $normalized['country'] ?? $record->country,
                    'city' => $normalized['city'] ?? $record->city,
                    'niche' => $normalized['niche'] ?? $record->niche,
                    'source_url' => $normalized['source_url'] ?? $record->source_url,
                ]);
                $processed++;
            } catch (\Throwable $e) {
                Log::warning('NormalizeRawLeadRecordsJob record failed', ['record_id' => $record->id, 'error' => $e->getMessage()]);
                $record->update(['processing_status' => RawLeadRecordStatus::Failed]);
                $failed++;
            }
        }

        $this->run->increment('total_processed', $processed);
        $this->run->increment('total_failed', $failed);

        Log::info('NormalizeRawLeadRecordsJob completed', ['run_id' => $this->run->id, 'processed' => $processed, 'failed' => $failed]);

        ApplyLeadCollectorRulesJob::dispatch($this->run);
    }
}
