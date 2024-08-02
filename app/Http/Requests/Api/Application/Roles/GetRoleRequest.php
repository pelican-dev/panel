<?php

namespace App\Http\Requests\Api\Application\Roles;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetRoleRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_ROLES;

    protected int $permission = AdminAcl::READ;
}
