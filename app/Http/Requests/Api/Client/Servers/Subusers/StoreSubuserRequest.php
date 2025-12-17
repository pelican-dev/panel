<?php

namespace App\Http\Requests\Api\Client\Servers\Subusers;

use App\Enums\SubuserPermission;

class StoreSubuserRequest extends SubuserRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::UserCreate;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|between:1,255',
            'permissions' => 'required|array',
            'permissions.*' => 'string',
        ];
    }
}
