<?php

namespace App\Http\Requests\Admin\Settings;

use App\Http\Requests\Admin\AdminFormRequest;

class AdvancedSettingsFormRequest extends AdminFormRequest
{
    /**
     * Return all the rules to apply to this request's data.
     */
    public function rules(): array
    {
        return [
            'recaptcha:enabled' => 'required|in:true,false',
            'recaptcha:secret_key' => 'required|string|max:255',
            'recaptcha:website_key' => 'required|string|max:255',
            'panel:guzzle:timeout' => 'required|integer|between:1,60',
            'panel:guzzle:connect_timeout' => 'required|integer|between:1,60',
            'panel:client_features:allocations:enabled' => 'required|in:true,false',
            'panel:client_features:allocations:range_start' => [
                'nullable',
                'required_if:panel:client_features:allocations:enabled,true',
                'integer',
                'between:1024,65535',
            ],
            'panel:client_features:allocations:range_end' => [
                'nullable',
                'required_if:panel:client_features:allocations:enabled,true',
                'integer',
                'between:1024,65535',
                'gt:panel:client_features:allocations:range_start',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'recaptcha:enabled' => 'reCAPTCHA Enabled',
            'recaptcha:secret_key' => 'reCAPTCHA Secret Key',
            'recaptcha:website_key' => 'reCAPTCHA Website Key',
            'panel:guzzle:timeout' => 'HTTP Request Timeout',
            'panel:guzzle:connect_timeout' => 'HTTP Connection Timeout',
            'panel:client_features:allocations:enabled' => 'Auto Create Allocations Enabled',
            'panel:client_features:allocations:range_start' => 'Starting Port',
            'panel:client_features:allocations:range_end' => 'Ending Port',
        ];
    }
}
