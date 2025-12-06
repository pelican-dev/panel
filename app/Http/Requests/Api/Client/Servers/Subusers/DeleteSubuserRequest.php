<?php

namespace App\Http\Requests\Api\Client\Servers\Subusers;

use App\Enums\SubuserPermission;

class DeleteSubuserRequest extends SubuserRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::UserDelete;
    }
}
