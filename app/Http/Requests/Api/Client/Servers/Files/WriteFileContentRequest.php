<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Contracts\Http\ClientPermissionsRequest;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class WriteFileContentRequest extends ClientApiRequest implements ClientPermissionsRequest
{
    /**
     * Returns the permissions string indicating which permission should be used to
     * validate that the authenticated user has permission to perform this action aganist
     * the given resource (server).
     */
    public function permission(): string
    {
        return Permission::ACTION_FILE_CREATE;
    }

    /**
     * There is no rule here for the file contents since we just use the body content
     * on the request to set the file contents. If nothing is passed that is fine since
     * it just means we want to set the file to be empty.
     */
    public function rules(): array
    {
        return [
            'file' => 'required|string',
        ];
    }
}
