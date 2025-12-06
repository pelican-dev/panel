<?php

namespace App\Http\Requests\Api\Client\Servers\Subusers;

use App\Enums\SubuserPermission;

class GetSubuserRequest extends SubuserRequest
{
    /**
     * Confirm that a user is able to view subusers for the specified server.
     */
    public function permission(): SubuserPermission
    {
        return SubuserPermission::UserRead;
    }
}
