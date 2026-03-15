<?php

namespace App\Jobs;

use App\Events\ImportRunFailed;
use App\ImportRowStatus;
use App\ImportRunStatus;
use App\Models\LeadImportRow;
use App\Models\LeadImportRun;
use App\Models\LeadSource;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncLeadSourceJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public LeadSource $leadSource
    ) {}

    public function handle(): void
    {
        $run = LeadImportRun::create([
            'lead_source_id' => $this->leadSource->id,
            'status' => ImportRunStatus::Running,
            'started_at' => now(),
            'stats' => ['total' => 0, 'imported' => 0, 'duplicates' => 0, 'invalid' => 0, 'failed' => 0],
        ]);

        try {
            $rows = $this->fetchRawData();
            foreach ($rows as $index => $raw) {
                LeadImportRow::create([
                    'lead_import_run_id' => $run->id,
                    'row_index' => $index,
                    'raw_data' => $raw,
                    'status' => ImportRowStatus::Pending,
                ]);
            }
            $run->update(['stats' => array_merge($run->stats ?? [], ['total' => count($rows)])]);
            $this->leadSource->update(['last_sync_at' => now()]);

            ValidateImportRowsJob::dispatch($run);
        } catch (\Throwable $e) {
            Log::error('SyncLeadSourceJob failed', ['run_id' => $run->id, 'error' => $e->getMessage()]);
            $run->update([
                'status' => ImportRunStatus::Failed,
                'completed_at' => now(),
                'error_message' => $e->getMessage(),
            ]);
            event(new ImportRunFailed($run));
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchRawData(): array
    {
        $config = $this->leadSource->config ?? [];
        if (isset($config['sample_rows']) && is_array($config['sample_rows'])) {
            return $config['sample_rows'];
        }
        if ($this->leadSource->type->value === 'import' && ! empty($config['path']) && is_string($config['path']) && file_exists($config['path'])) {
            return $this->readCsvOrJson($config['path']);
        }

        return [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function readCsvOrJson(string $path): array
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext === 'json') {
            $data = json_decode(file_get_contents($path), true);

            return is_array($data) ? (isset($data[0]) ? $data : [$data]) : [];
        }
        $rows = [];
        $handle = fopen($path, 'r');
        if (! $handle) {
            return [];
        }
        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);

            return [];
        }
        while (($line = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($header, $line) ?: [];
        }
        fclose($handle);

        return $rows;
    }
}
