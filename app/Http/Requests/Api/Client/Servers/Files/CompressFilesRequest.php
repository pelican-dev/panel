<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class CompressFilesRequest extends ClientApiRequest
{
    /**
     * Checks that the authenticated user is allowed to create archives for this server.
     */
    public function permission(): string
    {
        return Permission::ACTION_FILE_ARCHIVE;
    }

    public function rules(): array
    {
        return [
            'root' => 'sometimes|nullable|string',
            'files' => 'required|array',
            'files.*' => 'string',
            'name' => 'sometimes|nullable|string',
        ];
    }
}
