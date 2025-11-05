<?php

namespace App\Http\Requests\Api\Application\Users\ApiKeys;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\User;
use App\Services\Acl\Api\AdminAcl as Acl;

class GetUserApiKeysRequest extends ApplicationApiRequest
{
    protected ?string $resource = User::RESOURCE_NAME;

    protected int $permission = Acl::READ;
}
