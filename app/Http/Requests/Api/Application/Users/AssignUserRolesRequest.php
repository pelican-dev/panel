<?php

namespace App\Http\Requests\Api\Application\Users;

class AssignUserRolesRequest extends StoreUserRequest
{
    /**
     * Return the validation rules for this request.
     */
    public function rules(?array $rules = null): array
    {
        return [
            'roles' => 'array',
            'roles.*' => 'int',
        ];
    }
}
