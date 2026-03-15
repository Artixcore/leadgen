<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreImportedFileRequest extends FormRequest
{
    /**
     * Maximum file size in KB (10MB).
     */
    public const MAX_FILE_KB = 10240;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-lead-sources') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:csv,txt',
                'mimetypes:text/csv,text/plain',
                'max:'.self::MAX_FILE_KB,
            ],
            'lead_source_id' => ['required', 'integer', 'exists:lead_sources,id'],
        ];
    }
}
