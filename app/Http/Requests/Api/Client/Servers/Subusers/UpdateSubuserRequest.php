<?php

namespace App\Http\Requests\Api\Client\Servers\Subusers;

use App\Enums\SubuserPermission;

class UpdateSubuserRequest extends SubuserRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::UserUpdate;
    }

    public function rules(): array
    {
        return [
            'permissions' => 'required|array',
            'permissions.*' => 'string',
        ];
    }
}
