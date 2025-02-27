<?php

namespace App\Http\Requests\Api\Application\Nodes;

use App\Models\Node;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetNodesRequest extends ApplicationApiRequest
{
    protected ?string $resource = Node::RESOURCE_NAME;

    protected int $permission = AdminAcl::READ;
}
