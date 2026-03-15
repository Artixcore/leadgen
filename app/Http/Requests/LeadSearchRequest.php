<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LeadSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('use-lead-search') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'query' => ['required', 'string', 'max:500'],
            'target_service' => ['sometimes', 'nullable', 'string', 'max:100'],
            'target_niche' => ['sometimes', 'nullable', 'string', 'max:100'],
            'target_country' => ['sometimes', 'nullable', 'string', 'max:100'],
            'target_city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'company_size' => ['sometimes', 'nullable', 'string', 'max:50'],
            'min_score' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
            'verified_only' => ['sometimes', 'boolean'],
            'source_hints' => ['sometimes', 'array'],
            'source_hints.*' => ['string', 'max:50'],
            'include_website_analysis' => ['sometimes', 'boolean'],
            'async' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        $validated = $this->validated();
        unset($validated['query'], $validated['async']);

        return $validated;
    }
}
