<?php

namespace App\Http\Requests\Api\Application\Nodes;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Node;
use App\Services\Acl\Api\AdminAcl;

class DeleteNodeRequest extends ApplicationApiRequest
{
    protected ?string $resource = Node::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
