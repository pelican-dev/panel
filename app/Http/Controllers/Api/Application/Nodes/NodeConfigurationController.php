<?php

namespace App\Http\Controllers\Api\Application\Nodes;

use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Nodes\GetNodeRequest;
use App\Models\Node;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('Node', weight: 1)]
class NodeConfigurationController extends ApplicationApiController
{
    /**
     * Get node configuration
     *
     * Returns the configuration information for a node. This allows for automated deployments
     * to remote machines so long as an API key is provided to the machine to make the request
     * with, and the node is known.
     */
    public function __invoke(GetNodeRequest $request, Node $node): JsonResponse
    {
        return new JsonResponse($node->getConfiguration());
    }
}
