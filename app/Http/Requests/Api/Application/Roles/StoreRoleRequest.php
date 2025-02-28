<?php

namespace App\Http\Requests\Api\Application\Roles;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Role;

class StoreRoleRequest extends ApplicationApiRequest
{
    protected ?string $resource = Role::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /**
     * @param array<string, string|array<string>>|null $rules
     * @return array<string, string|array<string>>
     */
    public function rules(?array $rules = null): array
    {
        return [
            'name' => 'required|string',
        ];
    }
}
