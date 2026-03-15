<?php

namespace App\Jobs;

use App\Models\LeadCollectorRun;
use App\RawLeadRecordStatus;
use App\Services\LeadCollectors\LeadCollectorRuleEngine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ApplyLeadCollectorRulesJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public int $backoff = 30;

    public function __construct(
        public LeadCollectorRun $run
    ) {}

    public function handle(LeadCollectorRuleEngine $engine): void
    {
        Log::info('ApplyLeadCollectorRulesJob started', ['run_id' => $this->run->id]);

        $collector = $this->run->leadCollector;
        $records = $this->run->rawLeadRecords()->where('processing_status', RawLeadRecordStatus::Normalized)->get();
        $newCount = 0;
        $duplicateCount = 0;
        $rejectedCount = 0;

        foreach ($records as $record) {
            $result = $engine->evaluate($record, $collector);
            if (! $result['passed']) {
                $record->update(['processing_status' => RawLeadRecordStatus::Rejected]);
                $rejectedCount++;
            } else {
                $record->update(['processing_status' => RawLeadRecordStatus::Filtered]);
                $newCount++;
            }
        }

        $this->run->increment('total_new', $newCount);

        Log::info('ApplyLeadCollectorRulesJob completed', ['run_id' => $this->run->id, 'new' => $newCount, 'rejected' => $rejectedCount]);

        FinalizeCollectedLeadsJob::dispatch($this->run);
    }
}
