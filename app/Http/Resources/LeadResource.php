<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
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
            'full_name' => $this->full_name,
            'job_title' => $this->job_title,
            'email' => $this->email,
            'phone' => $this->phone,
            'company_name' => $this->company_name,
            'website' => $this->website,
            'linkedin_profile' => $this->linkedin_profile,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'industry' => $this->industry,
            'company_size' => $this->company_size,
            'revenue_range' => $this->revenue_range,
            'lead_source' => $this->lead_source,
            'verification_status' => $this->verification_status?->value,
            'quality_score' => $this->quality_score,
            'lead_status' => $this->lead_status?->value,
            'is_duplicate' => $this->is_duplicate,
            'notes' => $this->notes,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
