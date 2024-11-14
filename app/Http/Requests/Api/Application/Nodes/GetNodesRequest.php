<?php

namespace App\Http\Requests\Api\Application\Nodes;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Node;

class GetNodesRequest extends ApplicationApiRequest
{
    protected ?string $resource = Node::RESOURCE_NAME;

    protected int $permission = AdminAcl::READ;
}
