<?php

namespace App\Http\Requests\Api\Application\Plugins;

class ImportFilePluginRequest extends WritePluginRequest
{
    public function rules(): array
    {
        return [
            'url' => 'required|string',
        ];
    }
}
