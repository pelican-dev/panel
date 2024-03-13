<?php

namespace App\Http\Requests\Api\Application\Users;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetExternalUserRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_USERS;

    protected int $permission = AdminAcl::READ;
}
