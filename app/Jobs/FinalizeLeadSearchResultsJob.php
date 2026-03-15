<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Optional job to finalize search results (e.g. persist to DB) when pipeline is split.
 * Currently the full pipeline runs in RunLeadSearchJob; use this when splitting the pipeline.
 */
class FinalizeLeadSearchResultsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $leadSearchQueryId
    ) {}

    public function handle(): void
    {
        // No-op: finalization is done inside LeadSearchPipeline.
        // This job exists for future use when pipeline is split into multiple jobs.
    }
}
