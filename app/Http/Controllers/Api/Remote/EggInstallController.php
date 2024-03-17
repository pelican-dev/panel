<?php

namespace App\Http\Controllers\Api\Remote;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Servers\EnvironmentService;

class EggInstallController extends Controller
{
    /**
     * EggInstallController constructor.
     */
    public function __construct(private EnvironmentService $environment)
    {
    }

    /**
     * Handle request to get script and installation information for a server
     * that is being created on the node.
     */
    public function index(Request $request, string $uuid): JsonResponse
    {
        $node = $request->attributes->get('node');

        $server = Server::query()
            ->with('egg.scriptFrom')
            ->where('uuid', $uuid)
            ->where('node_id', $node->id)
            ->firstOrFail();

        $egg = $server->egg;

        return response()->json([
            'scripts' => [
                'install' => !$egg->copy_script_install ? null : str_replace(["\r\n", "\n", "\r"], "\n", $egg->copy_script_install),
                'privileged' => $egg->script_is_privileged,
            ],
            'config' => [
                'container' => $egg->copy_script_container,
                'entry' => $egg->copy_script_entry,
            ],
            'env' => $this->environment->handle($server),
        ]);
    }
}
