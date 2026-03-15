<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExportLeadsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('export-leads');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'format' => ['required', 'string', 'in:csv,xlsx'],
            'lead_ids' => ['required_without:list_id', 'array', 'min:1'],
            'lead_ids.*' => ['integer', 'exists:leads,id'],
            'list_id' => ['required_without:lead_ids', 'integer', 'exists:lead_lists,id'],
        ];
    }
}
