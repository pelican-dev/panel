<?php

namespace App\Http\Requests\Api\Application\Roles;

use App\Models\Role;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetRoleRequest extends ApplicationApiRequest
{
    protected ?string $resource = Role::RESOURCE_NAME;

    protected int $permission = AdminAcl::READ;
}
