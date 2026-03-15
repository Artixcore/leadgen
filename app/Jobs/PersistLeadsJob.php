<?php

namespace App\Jobs;

use App\ImportRowStatus;
use App\ImportRunStatus;
use App\Models\Lead;
use App\Models\LeadImportRun;
use App\VerificationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PersistLeadsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(
        public LeadImportRun $run
    ) {}

    public function handle(): void
    {
        $this->run->load('leadSource');
        $source = $this->run->leadSource;
        $stats = $this->run->stats ?? ['total' => 0, 'invalid' => 0, 'valid' => 0, 'duplicates' => 0, 'imported' => 0];
        $stats['imported'] = 0;
        $importedIds = [];

        $this->run->rows()
            ->where('status', ImportRowStatus::Valid)
            ->chunkById(200, function ($rows) use ($source, &$stats, &$importedIds): void {
                foreach ($rows as $row) {
                    $data = $row->normalized_data ?? [];
                    $lead = Lead::create(array_merge(
                        array_filter($data),
                        [
                            'lead_source_id' => $source->id,
                            'lead_source' => $source->name,
                            'verification_status' => VerificationStatus::Pending,
                        ]
                    ));
                    $row->update([
                        'lead_id' => $lead->id,
                        'status' => ImportRowStatus::Imported,
                    ]);
                    $stats['imported']++;
                    $importedIds[] = $lead->id;
                }
            });

        $this->run->update([
            'status' => ImportRunStatus::Completed,
            'completed_at' => now(),
            'stats' => $stats,
        ]);
        NotifyMatchingFiltersJob::dispatch($this->run, $importedIds);
    }
}
