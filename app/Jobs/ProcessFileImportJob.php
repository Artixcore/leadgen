<?php

namespace App\Jobs;

use App\ImportRowStatus;
use App\ImportRunStatus;
use App\Models\LeadImportRow;
use App\Models\LeadImportRun;
use App\Models\LeadSource;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ProcessFileImportJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public array $backoff = [60, 300];

    public function __construct(
        public string $path,
        public int $leadSourceId,
        public int $userId
    ) {}

    public function handle(): void
    {
        $disk = Storage::disk('local');
        $fullPath = $disk->path($this->path);

        if (! file_exists($fullPath)) {
            return;
        }

        $leadSource = LeadSource::findOrFail($this->leadSourceId);

        $run = LeadImportRun::create([
            'lead_source_id' => $leadSource->id,
            'triggered_by' => $this->userId,
            'status' => ImportRunStatus::Running,
            'started_at' => now(),
            'stats' => ['total' => 0, 'imported' => 0, 'duplicates' => 0, 'invalid' => 0, 'valid' => 0],
        ]);

        try {
            $handle = fopen($fullPath, 'r');
            if (! $handle) {
                $run->update(['status' => ImportRunStatus::Failed, 'error_message' => 'Could not open file']);
                $disk->delete($this->path);

                return;
            }

            $header = fgetcsv($handle);
            if (! $header) {
                fclose($handle);
                $run->update(['status' => ImportRunStatus::Failed, 'error_message' => 'Empty or invalid CSV']);
                $disk->delete($this->path);

                return;
            }

            $rowIndex = 0;
            $chunkSize = 500;
            $chunk = [];

            while (($line = fgetcsv($handle)) !== false) {
                $raw = array_combine($header, $line) ?: [];
                $chunk[] = ['row_index' => $rowIndex, 'raw_data' => $raw];
                $rowIndex++;

                if (count($chunk) >= $chunkSize) {
                    $this->createRows($run->id, $chunk);
                    $chunk = [];
                }
            }

            if ($chunk !== []) {
                $this->createRows($run->id, $chunk);
            }

            fclose($handle);

            $run->update(['stats' => array_merge($run->stats ?? [], ['total' => $rowIndex])]);
            $leadSource->update(['last_sync_at' => now()]);

            ValidateImportRowsJob::dispatch($run);
        } finally {
            $disk->delete($this->path);
        }
    }

    /**
     * @param  array<int, array{row_index: int, raw_data: array<string, mixed>}>  $chunk
     */
    private function createRows(int $runId, array $chunk): void
    {
        $rows = [];
        foreach ($chunk as $item) {
            $rows[] = [
                'lead_import_run_id' => $runId,
                'row_index' => $item['row_index'],
                'raw_data' => json_encode($item['raw_data']),
                'status' => ImportRowStatus::Pending->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        LeadImportRow::insert($rows);
    }
}
