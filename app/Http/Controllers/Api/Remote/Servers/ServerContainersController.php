<?php

namespace App\Http\Controllers\Api\Remote\Servers;

use App\Enums\ContainerStatus;
use App\Exceptions\Http\HttpForbiddenException;
use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServerContainersController extends Controller
{
    /**
     * Updates the server container's status on the Panel
     */
    public function status(Request $request, Server $server): JsonResponse
    {
        if (!$server->node->is($request->attributes->get('node'))) {
            throw new HttpForbiddenException('Requesting node does not have permission to access this server.');
        }

        $status = ContainerStatus::tryFrom($request->json('data.new_state')) ?? ContainerStatus::Missing;

        cache()->put("servers.$server->uuid.status", $status, now()->addHour());

        return new JsonResponse([]);
    }
}
