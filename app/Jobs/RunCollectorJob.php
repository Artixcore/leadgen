<?php

namespace App\Jobs;

use App\Events\ImportRunFailed;
use App\ImportRowStatus;
use App\ImportRunStatus;
use App\Models\LeadCollector;
use App\Models\LeadImportRow;
use App\Models\LeadImportRun;
use App\Services\CollectorDriverResolver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class RunCollectorJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public LeadCollector $collector,
        public ?int $triggeredByUserId = null
    ) {}

    public function handle(CollectorDriverResolver $resolver): void
    {
        $collector = $this->collector->load('leadSource');
        $run = LeadImportRun::create([
            'lead_source_id' => $collector->lead_source_id,
            'lead_collector_id' => $collector->id,
            'triggered_by' => $this->triggeredByUserId,
            'status' => ImportRunStatus::Running,
            'started_at' => now(),
            'stats' => ['total' => 0, 'imported' => 0, 'duplicates' => 0, 'invalid' => 0, 'failed' => 0],
        ]);

        try {
            $driver = $resolver->resolve($collector);
            $rows = $driver->fetch($collector);

            $chunkSize = 500;
            foreach (array_chunk($rows, $chunkSize, true) as $chunk) {
                $toInsert = [];
                foreach ($chunk as $index => $raw) {
                    $toInsert[] = [
                        'lead_import_run_id' => $run->id,
                        'row_index' => $index,
                        'raw_data' => json_encode($raw),
                        'status' => ImportRowStatus::Pending->value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                LeadImportRow::insert($toInsert);
            }

            $run->update(['stats' => array_merge($run->stats ?? [], ['total' => count($rows)])]);
            $collector->update(['last_run_at' => now()]);
            $collector->leadSource->update(['last_sync_at' => now()]);

            ValidateImportRowsJob::dispatch($run);
        } catch (\Throwable $e) {
            Log::error('RunCollectorJob failed', ['run_id' => $run->id, 'error' => $e->getMessage()]);
            $run->update([
                'status' => ImportRunStatus::Failed,
                'completed_at' => now(),
                'error_message' => $e->getMessage(),
            ]);
            event(new ImportRunFailed($run));
        }
    }
}
