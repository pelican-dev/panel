<?php

namespace App\Http\Requests\Api\Remote;

use Illuminate\Foundation\Http\FormRequest;

class SftpAuthenticationFormRequest extends FormRequest
{
    /**
     * Authenticate the request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules to apply to the request.
     *
     * @return array<string, string[]>
     */
    public function rules(): array
    {
        return [
            'type' => ['nullable', 'in:password,public_key'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Return only the fields that we are interested in from the request.
     * This will include empty fields as a null value.
     *
     * @return array<string, mixed>
     */
    public function normalize(): array
    {
        return $this->only(
            array_keys($this->rules())
        );
    }
}
