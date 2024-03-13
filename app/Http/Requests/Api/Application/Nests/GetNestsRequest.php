<?php

namespace App\Http\Requests\Api\Application\Nests;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetNestsRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_NESTS;

    protected int $permission = AdminAcl::READ;
}
