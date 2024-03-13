<?php

namespace App\Http\Requests\Api\Client\Servers\Subusers;

use App\Models\Permission;

class DeleteSubuserRequest extends SubuserRequest
{
    public function permission(): string
    {
        return Permission::ACTION_USER_DELETE;
    }
}
