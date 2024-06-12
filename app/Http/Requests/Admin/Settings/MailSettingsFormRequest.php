<?php

namespace App\Http\Requests\Admin\Settings;

use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\AdminFormRequest;

class MailSettingsFormRequest extends AdminFormRequest
{
    /**
     * Return rules to validate mail settings POST data against.
     */
    public function rules(): array
    {
        return [
            'mail:mailers:smtp:host' => 'required|string',
            'mail:mailers:smtp:port' => 'required|integer|between:1,65535',
            'mail:mailers:smtp:encryption' => ['present', Rule::in([null, 'tls', 'ssl'])],
            'mail:mailers:smtp:username' => 'nullable|string|max:255',
            'mail:mailers:smtp:password' => 'nullable|string|max:255',
            'mail:from:address' => 'required|string|email',
            'mail:from:name' => 'nullable|string|max:255',
        ];
    }

    /**
     * Override the default normalization function for this type of request
     * as we need to accept empty values on the keys.
     */
    public function normalize(array $only = null): array
    {
        $keys = array_flip(array_keys($this->rules()));

        if (empty($this->input('mail:mailers:smtp:password'))) {
            unset($keys['mail:mailers:smtp:password']);
        }

        return $this->only(array_flip($keys));
    }
}
