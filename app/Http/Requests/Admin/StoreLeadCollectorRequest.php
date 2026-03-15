<?php

namespace App\Http\Requests\Admin;

use App\CollectorStatus;
use App\CollectorType;
use App\LeadCollectorSourceType;
use App\LeadCollectorTargetService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeadCollectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-lead-collectors');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:lead_collectors,slug'],
            'source_name' => ['nullable', 'string', 'max:255'],
            'source_type' => ['nullable', 'string', 'in:'.implode(',', array_column(LeadCollectorSourceType::cases(), 'value'))],
            'type' => ['required', 'string', 'in:'.implode(',', array_column(CollectorType::cases(), 'value'))],
            'target_service' => ['nullable', 'string', 'in:'.implode(',', array_column(LeadCollectorTargetService::cases(), 'value'))],
            'target_niche' => ['nullable', 'string', 'max:255'],
            'target_country' => ['nullable', 'string', 'max:255'],
            'target_city' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable', 'string'],
            'filters_json' => ['nullable', 'string'],
            'config' => ['nullable', 'string'],
            'trust_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'priority' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:'.implode(',', array_column(CollectorStatus::cases(), 'value'))],
            'is_active' => ['nullable', 'boolean'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'next_run_at' => ['nullable', 'date'],
            'lead_source_id' => ['required_unless:create_lead_source,1', 'nullable', 'exists:lead_sources,id'],
            'create_lead_source' => ['nullable', 'boolean'],
        ];
    }
}
