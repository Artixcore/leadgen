<?php

namespace App\Http\Requests;

use App\VerificationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSavedFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('search-leads') && $this->user()->can('receive-notifications');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'criteria' => ['required', 'array'],
            'criteria.q' => ['sometimes', 'nullable', 'string', 'max:255'],
            'criteria.industry' => ['sometimes', 'nullable', 'string', 'max:255'],
            'criteria.niche' => ['sometimes', 'nullable', 'string', 'max:255'],
            'criteria.country' => ['sometimes', 'nullable', 'string', 'max:255'],
            'criteria.city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'criteria.job_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'criteria.company_size' => ['sometimes', 'nullable', 'string', 'max:255'],
            'criteria.revenue_range' => ['sometimes', 'nullable', 'string', 'max:255'],
            'criteria.lead_source' => ['sometimes', 'nullable', 'string', 'max:255'],
            'criteria.verification_status' => ['sometimes', 'nullable', 'string', 'in:'.implode(',', array_map(fn ($c) => $c->value, VerificationStatus::cases()))],
            'criteria.quality_score_min' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
            'criteria.exclude_duplicates' => ['sometimes', 'boolean'],
            'criteria.freshness' => ['sometimes', 'nullable', 'string', 'in:fresh,stale,unknown'],
            'criteria.recently_added_days' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:365'],
            'criteria.has_email' => ['sometimes', 'boolean'],
            'criteria.has_phone' => ['sometimes', 'boolean'],
            'criteria.has_linkedin' => ['sometimes', 'boolean'],
            'criteria.sort' => ['sometimes', 'nullable', 'string', 'in:newest,highest_quality,most_relevant'],
            'criteria.sort_dir' => ['sometimes', 'nullable', 'string', 'in:asc,desc'],
        ];
    }
}
