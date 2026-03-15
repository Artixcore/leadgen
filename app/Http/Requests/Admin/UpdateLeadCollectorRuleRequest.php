<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadCollectorRuleRequest extends FormRequest
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
            'rule_key' => ['required', 'string', 'max:255'],
            'rule_operator' => ['required', 'string', 'in:eq,neq,exists,not_exists'],
            'rule_value' => ['nullable', 'string'],
            'score_weight' => ['nullable', 'integer'],
            'is_required' => ['nullable', 'boolean'],
        ];
    }
}
