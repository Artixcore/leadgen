<?php

namespace App\Console\Commands;

use App\Jobs\SyncLeadSourceJob;
use App\LeadSourceStatus;
use App\Models\LeadSource;
use Cron\CronExpression;
use Illuminate\Console\Command;

class SyncScheduledLeadSources extends Command
{
    protected $signature = 'lead-sources:sync-scheduled';

    protected $description = 'Dispatch sync jobs for lead sources that are due by their import frequency cron.';

    public function handle(): int
    {
        $sources = LeadSource::query()
            ->where('status', LeadSourceStatus::Active)
            ->whereNotNull('import_frequency')
            ->get();

        $dispatched = 0;
        foreach ($sources as $source) {
            try {
                $cron = new CronExpression($source->import_frequency);
                if ($cron->isDue('now')) {
                    SyncLeadSourceJob::dispatch($source);
                    $dispatched++;
                }
            } catch (\Throwable) {
                continue;
            }
        }

        if ($dispatched > 0) {
            $this->info("Dispatched {$dispatched} sync job(s).");
        }

        return self::SUCCESS;
    }
}
