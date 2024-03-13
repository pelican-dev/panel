<?php

namespace App\Http\Requests\Api\Application\Allocations;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetAllocationsRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_ALLOCATIONS;

    protected int $permission = AdminAcl::READ;
}
