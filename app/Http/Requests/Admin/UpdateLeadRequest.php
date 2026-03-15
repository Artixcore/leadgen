<?php

namespace App\Http\Requests\Admin;

use App\LeadStatus;
use App\VerificationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage-leads');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'job_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'company_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'website' => ['sometimes', 'nullable', 'string', 'max:255'],
            'linkedin_profile' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country' => ['sometimes', 'nullable', 'string', 'max:255'],
            'state' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'industry' => ['sometimes', 'nullable', 'string', 'max:255'],
            'niche' => ['sometimes', 'nullable', 'string', 'max:255'],
            'company_size' => ['sometimes', 'nullable', 'string', 'max:255'],
            'revenue_range' => ['sometimes', 'nullable', 'string', 'max:255'],
            'lead_source_id' => ['sometimes', 'nullable', 'integer', 'exists:lead_sources,id'],
            'verification_status' => ['sometimes', 'nullable', 'string', 'in:'.implode(',', array_map(fn ($c) => $c->value, VerificationStatus::cases()))],
            'quality_score' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
            'lead_status' => ['sometimes', 'nullable', 'string', 'in:'.implode(',', array_map(fn ($c) => $c->value, LeadStatus::cases()))],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
