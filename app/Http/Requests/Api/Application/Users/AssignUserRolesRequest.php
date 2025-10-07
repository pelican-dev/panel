<?php

namespace App\Http\Requests\Api\Application\Users;

class AssignUserRolesRequest extends StoreUserRequest
{
    /** @return array<array-key, string|string[]> */
    public function rules(?array $rules = null): array
    {
        return [
            'roles' => 'required|array',
            'roles.*' => 'integer|exists:roles,id',
        ];
    }
}
