<?php

namespace App\Http\Requests\Api\Remote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportBackupCompleteRequest extends FormRequest
{
    /** @return array<array-key, string|string[]> */
    public function rules(): array
    {
        return [
            'successful' => [
                'required',
                'boolean',
            ],
            'checksum' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => $this->boolean('successful')),
            ],
            'checksum_type' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => $this->boolean('successful')),
            ],
            'size' => [
                'nullable',
                'numeric',
                Rule::requiredIf(fn () => $this->boolean('successful')),
            ],
            'parts' => [
                'nullable',
                'array',
            ],
            'parts.*.etag' => [
                'required',
                'string',
            ],
            'parts.*.part_number' => [
                'required',
                'numeric',
            ],
        ];
    }
}
