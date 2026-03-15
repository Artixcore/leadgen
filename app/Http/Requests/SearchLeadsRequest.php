<?php

namespace App\Http\Requests;

use App\VerificationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchLeadsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('search-leads');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:255'],
            'industry' => ['sometimes', 'nullable', 'string', 'max:255'],
            'niche' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'job_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'company_size' => ['sometimes', 'nullable', 'string', 'max:255'],
            'revenue_range' => ['sometimes', 'nullable', 'string', 'max:255'],
            'lead_source' => ['sometimes', 'nullable', 'string', 'max:255'],
            'verification_status' => ['sometimes', 'nullable', 'string', 'in:'.implode(',', array_map(fn ($c) => $c->value, VerificationStatus::cases()))],
            'quality_score_min' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
            'exclude_duplicates' => ['sometimes', 'boolean'],
            'freshness' => ['sometimes', 'nullable', 'string', 'in:fresh,stale,unknown'],
            'recently_added_days' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:365'],
            'has_email' => ['sometimes', 'boolean'],
            'has_phone' => ['sometimes', 'boolean'],
            'has_linkedin' => ['sometimes', 'boolean'],
            'sort' => ['sometimes', 'nullable', 'string', 'in:newest,highest_quality,most_relevant'],
            'sort_dir' => ['sometimes', 'nullable', 'string', 'in:asc,desc'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'saved_filter_id' => ['sometimes', 'nullable', 'integer', 'exists:saved_filters,id'],
        ];
    }
}
