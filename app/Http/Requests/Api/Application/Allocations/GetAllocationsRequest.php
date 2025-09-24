<?php

namespace App\Http\Requests\Api\Application\Allocations;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Allocation;
use App\Services\Acl\Api\AdminAcl;

class GetAllocationsRequest extends ApplicationApiRequest
{
    protected ?string $resource = Allocation::RESOURCE_NAME;

    protected int $permission = AdminAcl::READ;
}
