<?php

namespace App\Jobs;

use App\Models\LeadSearchQuery;
use App\Services\LeadSearch\LeadSearchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class RunLeadSearchJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public LeadSearchQuery $leadSearchQuery
    ) {}

    public function handle(LeadSearchService $leadSearchService): void
    {
        $query = $this->leadSearchQuery->fresh();
        if (! $query || $query->status !== 'pending') {
            return;
        }

        try {
            $leadSearchService->runPipeline($query);
        } catch (\Throwable $e) {
            Log::error('RunLeadSearchJob failed', [
                'query_id' => $query->id,
                'error' => $e->getMessage(),
            ]);
            $query->update(['status' => 'failed']);
            throw $e;
        }
    }
}
