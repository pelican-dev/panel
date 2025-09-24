<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Contracts\Http\ClientPermissionsRequest;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class PullFileRequest extends ClientApiRequest implements ClientPermissionsRequest
{
    public function permission(): string
    {
        return Permission::ACTION_FILE_CREATE;
    }

    public function rules(): array
    {
        return [
            'url' => 'required|string|url',
            'directory' => 'nullable|string',
            'filename' => 'nullable|string',
            'use_header' => 'boolean',
            'foreground' => 'boolean',
        ];
    }
}
