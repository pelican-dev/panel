<?php

namespace App\Http\Requests\Api\Client\Servers\Databases;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class RotatePasswordRequest extends ClientApiRequest
{
    /**
     * Check that the user has permission to rotate the password.
     */
    public function permission(): SubuserPermission
    {
        return SubuserPermission::DatabaseUpdate;
    }
}
