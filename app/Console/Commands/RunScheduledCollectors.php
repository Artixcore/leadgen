<?php

namespace App\Console\Commands;

use App\Jobs\RunCollectorJob;
use App\Models\LeadCollector;
use Cron\CronExpression;
use Illuminate\Console\Command;

class RunScheduledCollectors extends Command
{
    protected $signature = 'collectors:run-scheduled';

    protected $description = 'Dispatch run jobs for lead collectors that are due by their schedule cron.';

    public function handle(): int
    {
        $collectors = LeadCollector::query()
            ->active()
            ->whereNotNull('schedule')
            ->get();

        $dispatched = 0;
        foreach ($collectors as $collector) {
            try {
                $cron = new CronExpression($collector->schedule);
                if ($cron->isDue('now')) {
                    RunCollectorJob::dispatch($collector);
                    $dispatched++;
                }
            } catch (\Throwable) {
                continue;
            }
        }

        if ($dispatched > 0) {
            $this->info("Dispatched {$dispatched} collector job(s).");
        }

        return self::SUCCESS;
    }
}
