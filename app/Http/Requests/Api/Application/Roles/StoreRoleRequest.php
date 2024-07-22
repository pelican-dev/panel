<?php

namespace App\Http\Requests\Api\Application\Roles;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class StoreRoleRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_ROLES;

    protected int $permission = AdminAcl::WRITE;

    public function rules(array $rules = null): array
    {
        return [
            'name' => 'required|string',
            'guard_name' => 'nullable|string',
        ];
    }
}
