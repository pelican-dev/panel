<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<array-key, string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ];
    }
}
