<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'leads_per_month' => $this->leads_per_month,
            'exports_per_month' => $this->exports_per_month,
            'saved_lists_count' => $this->saved_lists_count,
            'team_members_limit' => $this->team_members_limit,
            'api_access' => $this->api_access,
            'advanced_filters' => $this->advanced_filters,
            'trial_days' => $this->trial_days,
        ];
    }
}
