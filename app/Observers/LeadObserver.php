<?php

namespace App\Observers;

use App\Models\Lead;

class LeadObserver
{
    /**
     * Handle the Lead "saving" event.
     */
    public function saving(Lead $lead): void
    {
        if (blank($lead->email)) {
            $lead->is_duplicate = false;
            $lead->duplicate_of_lead_id = null;

            return;
        }

        $existing = Lead::query()
            ->where('email', $lead->email)
            ->when($lead->exists, fn ($q) => $q->where('id', '!=', $lead->id))
            ->orderBy('id')
            ->first();

        if ($existing) {
            $lead->is_duplicate = true;
            $lead->duplicate_of_lead_id = $existing->id;
        } else {
            $lead->is_duplicate = false;
            $lead->duplicate_of_lead_id = null;
        }

        if ($lead->quality_score === null) {
            $lead->quality_score = $this->computeCompletenessScore($lead);
        }
    }

    private function computeCompletenessScore(Lead $lead): int
    {
        $fields = [
            $lead->full_name,
            $lead->job_title,
            $lead->email,
            $lead->phone,
            $lead->company_name,
            $lead->website,
            $lead->linkedin_profile,
            $lead->country,
            $lead->state,
            $lead->city,
            $lead->industry,
            $lead->company_size,
            $lead->revenue_range,
            $lead->lead_source,
        ];
        $filled = count(array_filter($fields, fn ($v) => filled($v)));

        return (int) round(($filled / count($fields)) * 100);
    }

    /**
     * Handle the Lead "created" event.
     */
    public function created(Lead $lead): void
    {
        //
    }

    /**
     * Handle the Lead "updated" event.
     */
    public function updated(Lead $lead): void
    {
        //
    }

    /**
     * Handle the Lead "deleted" event.
     */
    public function deleted(Lead $lead): void
    {
        //
    }

    /**
     * Handle the Lead "restored" event.
     */
    public function restored(Lead $lead): void
    {
        //
    }

    /**
     * Handle the Lead "force deleted" event.
     */
    public function forceDeleted(Lead $lead): void
    {
        //
    }
}
