<?php

namespace App\Http\Requests\Api\Application\Allocations;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Allocation;

class DeleteAllocationRequest extends ApplicationApiRequest
{
    protected ?string $resource = Allocation::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
