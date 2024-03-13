<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Models\Permission;
use App\Contracts\Http\ClientPermissionsRequest;
use App\Http\Requests\Api\Client\ClientApiRequest;

class DeleteFileRequest extends ClientApiRequest implements ClientPermissionsRequest
{
    public function permission(): string
    {
        return Permission::ACTION_FILE_DELETE;
    }

    public function rules(): array
    {
        return [
            'root' => 'required|nullable|string',
            'files' => 'required|array',
            'files.*' => 'string',
        ];
    }
}
