<?php

namespace App\Http\Requests\Api\Application\Users;

class AssignUserRolesRequest extends StoreUserRequest
{
    /** @return array<string, string|array<string>> */
    public function rules(?array $rules = null): array
    {
        return [
            'roles' => 'array',
            'roles.*' => 'int',
        ];
    }
}
