<?php

namespace App\Services\Nodes;

use App\Http\Controllers\Admin\NodeAutoDeployController;
use App\Models\Node;

class NodeAutoDeployService
{
    /**
     * NodeAutoDeployService constructor.
     */
    public function __construct(
        private readonly NodeAutoDeployController $nodeAutoDeployController
    ) {
    }

    public function handle(Node $node): ?string
    {
        $service = $this->nodeAutoDeployController->__invoke(request(), $node);
        $token = $service->getData()->token;
        if ($token) {
            return 'sudo wings configure ' .
                '--panel-url ' .
                config('app.url') .
                ' --token ' . $token .
                ' --node ' . $node->id .
                ' ' .
                (request()->isSecure() ? '' : ' --allow-insecure');
        } else {
            return null;
        }
    }
}
