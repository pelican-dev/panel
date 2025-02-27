<?php

namespace App\Http\Requests\Api\Application\Allocations;

use App\Models\Allocation;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class DeleteAllocationRequest extends ApplicationApiRequest
{
    protected ?string $resource = Allocation::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
