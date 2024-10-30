<?php

namespace App\Http\Controllers\Admin;

use App\Models\Node;
use App\Http\Controllers\Controller;
use App\Services\Nodes\NodeAutoDeployService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NodeAutoDeployController extends Controller
{
    /**
     * NodeAutoDeployController constructor.
     */
    public function __construct(
        private readonly NodeAutoDeployService $nodeAutoDeployService
    ) {
    }

    /**
     * Handles the API request and returns the deployment command.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function __invoke(Request $request, Node $node): JsonResponse
    {
        $command = $this->nodeAutoDeployService->handle($request, $node);

        return new JsonResponse(['command' => $command]);
    }
}
