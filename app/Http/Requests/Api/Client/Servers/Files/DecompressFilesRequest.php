<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class DecompressFilesRequest extends ClientApiRequest
{
    /**
     * Checks that the authenticated user is allowed to create new files for the server. We don't
     * rely on the archive permission here as it makes more sense to make sure the user can create
     * additional files rather than make an archive.
     */
    public function permission(): SubuserPermission
    {
        return SubuserPermission::FileCreate;
    }

    public function rules(): array
    {
        return [
            'root' => 'sometimes|nullable|string',
            'file' => 'required|string',
        ];
    }
}
