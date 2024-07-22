<?php

namespace App\Http\Requests\Api\Application\Roles;

use App\Models\Role;

class UpdateRoleRequest extends StoreRoleRequest
{
    public function rules(array $rules = null): array
    {
        /** @var Role $role */
        $role = $this->route()->parameter('role');

        return $rules ?? Role::getRulesForUpdate($role->name);
    }
}
