<?php

namespace App\Jobs;

use App\ImportRowStatus;
use App\Models\Lead;
use App\Models\LeadImportRun;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeduplicateImportRowsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(
        public LeadImportRun $run
    ) {}

    public function handle(): void
    {
        $this->run->load(['rows' => fn ($q) => $q->where('status', ImportRowStatus::Valid)]);
        $stats = $this->run->stats ?? ['total' => 0, 'invalid' => 0, 'valid' => 0, 'duplicates' => 0];
        $stats['duplicates'] = 0;

        foreach ($this->run->rows as $row) {
            $data = $row->normalized_data ?? [];
            $email = $data['email'] ?? null;
            if (blank($email)) {
                continue;
            }
            $existing = Lead::query()
                ->where('email', $email)
                ->when(! empty($data['company_name']), fn ($q) => $q->where('company_name', $data['company_name']))
                ->first();
            if ($existing) {
                $row->update([
                    'status' => ImportRowStatus::Duplicate,
                    'lead_id' => $existing->id,
                ]);
                $stats['duplicates']++;
            }
        }
        $this->run->update(['stats' => $stats]);
        PersistLeadsJob::dispatch($this->run);
    }
}
