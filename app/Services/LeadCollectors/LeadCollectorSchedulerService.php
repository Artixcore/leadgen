<?php

namespace App\Services\LeadCollectors;

use App\Models\LeadCollector;
use Carbon\Carbon;

class LeadCollectorSchedulerService
{
    /**
     * Compute the next run timestamp from the collector's schedule (cron-style or simple).
     * Returns null if schedule is empty or invalid.
     */
    public function nextRunAt(LeadCollector $collector): ?Carbon
    {
        $schedule = $collector->schedule;
        if (empty($schedule)) {
            return null;
        }

        // Simple cron-like: "0 * * * *" (min hour day month dow) or "hourly", "daily"
        if ($schedule === 'hourly') {
            return now()->addHour()->startOfHour();
        }
        if ($schedule === 'daily') {
            return now()->addDay()->startOfDay();
        }
        if (preg_match('/^\d+\s+\d+\s+\*\s+\*\s+\*$/', $schedule)) {
            // Simplified: assume next occurrence today or tomorrow
            return now()->addDay()->startOfDay();
        }

        return null;
    }

    /**
     * Update collector's next_run_at from schedule.
     */
    public function scheduleNextRun(LeadCollector $collector): void
    {
        $next = $this->nextRunAt($collector);
        if ($next !== null) {
            $collector->update(['next_run_at' => $next]);
        }
    }
}
