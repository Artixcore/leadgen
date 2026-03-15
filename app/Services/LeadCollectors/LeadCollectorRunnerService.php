<?php

namespace App\Services\LeadCollectors;

use App\Jobs\NormalizeRawLeadRecordsJob;
use App\LeadCollectorRunStatus;
use App\Models\LeadCollector;
use App\Models\LeadCollectorRun;
use App\Models\RawLeadRecord;
use App\RawLeadRecordStatus;
use Illuminate\Support\Facades\Log;

class LeadCollectorRunnerService
{
    public function __construct(
        protected CollectorDriverResolver $driverResolver
    ) {}

    public function run(LeadCollector $collector, string $runType = 'manual', ?int $triggeredByUserId = null): LeadCollectorRun
    {
        $collector->load('leadSource');
        $run = LeadCollectorRun::create([
            'lead_collector_id' => $collector->id,
            'run_type' => $runType,
            'status' => LeadCollectorRunStatus::Running,
            'started_at' => now(),
            'total_found' => 0,
            'total_processed' => 0,
            'total_new' => 0,
            'total_duplicates' => 0,
            'total_failed' => 0,
        ]);

        Log::info('Lead collector run started', ['collector_id' => $collector->id, 'run_id' => $run->id]);

        try {
            $driver = $this->driverResolver->resolve($collector);
            $rows = $driver->collect($collector);
            $totalFound = count($rows);

            $run->update(['total_found' => $totalFound]);

            $discoveredAt = now();
            foreach ($rows as $index => $raw) {
                RawLeadRecord::create([
                    'lead_collector_id' => $collector->id,
                    'lead_collector_run_id' => $run->id,
                    'source_record_id' => $raw['id'] ?? $raw['source_record_id'] ?? null,
                    'company_name' => $raw['company_name'] ?? $raw['company'] ?? null,
                    'website' => $raw['website'] ?? null,
                    'email' => $raw['email'] ?? null,
                    'phone' => $raw['phone'] ?? null,
                    'address' => $raw['address'] ?? null,
                    'country' => $raw['country'] ?? null,
                    'city' => $raw['city'] ?? null,
                    'niche' => $raw['niche'] ?? null,
                    'source_url' => $raw['source_url'] ?? $raw['url'] ?? null,
                    'raw_payload' => $raw,
                    'processing_status' => RawLeadRecordStatus::Pending,
                    'discovered_at' => $discoveredAt,
                ]);
            }

            $run->update([
                'status' => LeadCollectorRunStatus::Completed,
                'finished_at' => now(),
            ]);
            $collector->update(['last_run_at' => now()]);
            if ($collector->leadSource) {
                $collector->leadSource->update(['last_sync_at' => now()]);
            }

            Log::info('Lead collector run completed', ['run_id' => $run->id, 'total_found' => $totalFound]);

            NormalizeRawLeadRecordsJob::dispatch($run);
        } catch (\Throwable $e) {
            Log::error('Lead collector run failed', ['run_id' => $run->id, 'error' => $e->getMessage()]);
            $run->update([
                'status' => LeadCollectorRunStatus::Failed,
                'finished_at' => now(),
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }

        return $run->fresh();
    }
}
