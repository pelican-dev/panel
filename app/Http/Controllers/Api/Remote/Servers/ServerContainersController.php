<?php

namespace App\Http\Controllers\Api\Remote\Servers;

use App\Http\Requests\Api\Remote\ServerRequest;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ServerContainersController extends Controller
{
    /**
     * Updates the server container's status on the Panel
     */
    public function status(ServerRequest $request, Server $server): JsonResponse
    {
        $status = fluent($request->json()->all())->get('data.new_state');

        cache()->put("servers.$server->uuid.status", $status, now()->addHour());

        return new JsonResponse([]);
    }
}
