<?php

namespace App\Jobs;

use App\Models\LeadCollectorRun;
use App\RawLeadRecordStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FinalizeCollectedLeadsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(
        public LeadCollectorRun $run
    ) {}

    public function handle(): void
    {
        Log::info('FinalizeCollectedLeadsJob started', ['run_id' => $this->run->id]);

        $count = $this->run->rawLeadRecords()
            ->where('processing_status', RawLeadRecordStatus::Filtered)
            ->update(['processing_status' => RawLeadRecordStatus::Accepted]);

        Log::info('FinalizeCollectedLeadsJob completed', ['run_id' => $this->run->id, 'accepted' => $count]);

        // TODO: Future: publish accepted raw records to main leads table.
    }
}
