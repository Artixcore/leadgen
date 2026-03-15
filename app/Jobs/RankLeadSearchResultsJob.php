<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Optional job to run ranking step separately for very large searches.
 * Currently the full pipeline runs in RunLeadSearchJob; use this when splitting the pipeline.
 */
class RankLeadSearchResultsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $leadSearchQueryId
    ) {}

    public function handle(): void
    {
        // No-op: ranking is done inside LeadSearchPipeline.
        // This job exists for future use when pipeline is split into multiple jobs.
    }
}
