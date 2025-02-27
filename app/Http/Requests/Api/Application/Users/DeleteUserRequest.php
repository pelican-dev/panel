<?php

namespace App\Http\Requests\Api\Application\Users;

use App\Models\User;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class DeleteUserRequest extends ApplicationApiRequest
{
    protected ?string $resource = User::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
