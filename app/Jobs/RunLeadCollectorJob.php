<?php

namespace App\Jobs;

use App\Models\LeadCollector;
use App\Services\LeadCollectors\LeadCollectorRunnerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class RunLeadCollectorJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public LeadCollector $collector,
        public string $runType = 'manual',
        public ?int $triggeredByUserId = null
    ) {}

    public function handle(LeadCollectorRunnerService $runner): void
    {
        Log::info('RunLeadCollectorJob started', ['collector_id' => $this->collector->id]);
        $runner->run($this->collector, $this->runType, $this->triggeredByUserId);
    }
}
