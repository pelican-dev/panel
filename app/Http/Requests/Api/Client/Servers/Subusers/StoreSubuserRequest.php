<?php

namespace App\Http\Requests\Api\Client\Servers\Subusers;

use App\Models\Permission;

class StoreSubuserRequest extends SubuserRequest
{
    public function permission(): string
    {
        return Permission::ACTION_USER_CREATE;
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
