<?php

namespace App\Http\Requests\Api\Remote;

use Illuminate\Foundation\Http\FormRequest;

class InstallationDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string|string[]>
     */
    public function rules(): array
    {
        return [
            'successful' => 'present|boolean',
            'reinstall' => 'sometimes|boolean',
        ];
    }
}
