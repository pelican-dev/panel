<?php

namespace App\Http\Requests\Api\Application\Nodes;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetNodesRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_NODES;

    protected int $permission = AdminAcl::READ;
}
