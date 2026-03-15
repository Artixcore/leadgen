<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\LeadImportRun;
use App\Models\SavedFilter;
use App\Notifications\NewLeadsMatchSavedFilterNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyMatchingFiltersJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public LeadImportRun $run,
        /** @var array<int, int> */
        public array $leadIds = []
    ) {}

    public function handle(): void
    {
        if ($this->leadIds === []) {
            return;
        }
        if (! class_exists(SavedFilter::class)) {
            return;
        }
        $leads = Lead::query()->whereIn('id', $this->leadIds)->get();
        $filters = SavedFilter::query()->with('user')->get();
        foreach ($filters as $filter) {
            if (! $filter->user?->can('receive-notifications')) {
                continue;
            }
            foreach ($leads as $lead) {
                if ($filter->matches($lead)) {
                    $filter->user->notify(new NewLeadsMatchSavedFilterNotification($filter, $lead));
                    break;
                }
            }
        }
    }
}
