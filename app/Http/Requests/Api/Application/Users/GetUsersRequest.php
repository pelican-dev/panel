<?php

namespace App\Http\Requests\Api\Application\Users;

use App\Services\Acl\Api\AdminAcl as Acl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetUsersRequest extends ApplicationApiRequest
{
    protected ?string $resource = Acl::RESOURCE_USERS;

    protected int $permission = Acl::READ;
}
