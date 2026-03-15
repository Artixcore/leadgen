<?php

namespace App\Http\Requests\Admin;

use App\LeadSourceStatus;
use App\LeadSourceType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeadSourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-lead-sources');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:'.implode(',', array_column(LeadSourceType::cases(), 'value'))],
            'status' => ['required', 'string', 'in:'.implode(',', array_column(LeadSourceStatus::cases(), 'value'))],
            'reliability_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'import_frequency' => ['nullable', 'string', 'max:255'],
            'validation_rules' => ['nullable', 'array'],
            'validation_rules.required' => ['sometimes', 'array'],
            'validation_rules.required.*' => ['string'],
            'validation_rules.email_format' => ['sometimes', 'boolean'],
            'config' => ['nullable', 'array'],
        ];
    }
}
