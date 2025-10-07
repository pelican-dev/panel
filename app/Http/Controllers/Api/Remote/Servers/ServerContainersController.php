<?php

namespace App\Http\Controllers\Api\Remote\Servers;

use App\Enums\ContainerStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Remote\ServerRequest;
use App\Models\Server;
use Illuminate\Http\JsonResponse;

class ServerContainersController extends Controller
{
    /**
     * Updates the server container's status on the Panel
     */
    public function status(ServerRequest $request, Server $server): JsonResponse
    {
        $status = ContainerStatus::tryFrom($request->json('data.new_state')) ?? ContainerStatus::Missing;

        cache()->put("servers.$server->uuid.status", $status, now()->addHour());

        return new JsonResponse([]);
    }
}
