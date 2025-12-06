<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class CreateFolderRequest extends ClientApiRequest
{
    /**
     * Checks that the authenticated user is allowed to create files on the server.
     */
    public function permission(): SubuserPermission
    {
        return SubuserPermission::FileCreate;
    }

    public function rules(): array
    {
        return [
            'root' => 'sometimes|nullable|string',
            'name' => 'required|string',
        ];
    }
}
