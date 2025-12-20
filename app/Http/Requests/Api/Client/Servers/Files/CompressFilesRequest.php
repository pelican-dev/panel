<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class CompressFilesRequest extends ClientApiRequest
{
    /**
     * Checks that the authenticated user is allowed to create archives for this server.
     */
    public function permission(): SubuserPermission
    {
        return SubuserPermission::FileArchive;
    }

    public function rules(): array
    {
        return [
            'root' => 'sometimes|nullable|string',
            'files' => 'required|array',
            'files.*' => 'string',
            'name' => 'sometimes|nullable|string',
            'extension' => 'sometimes|in:zip,tgz,tar.gz,txz,tar.xz,tbz2,tar.bz2',
        ];
    }
}
