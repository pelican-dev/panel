<?php

namespace App\Http\Controllers\Api\Remote\Servers;

use App\Enums\ServerState;
use App\Events\Server\Installed as ServerInstalled;
use App\Exceptions\Model\DataValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Remote\InstallationDataRequest;
use App\Http\Requests\Api\Remote\ServerRequest;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ServerInstallController extends Controller
{
    /**
     * Returns installation information for a server.
     */
    public function index(ServerRequest $request, Server $server): JsonResponse
    {
        return new JsonResponse([
            'container_image' => $server->egg->copy_script_container,
            'entrypoint' => $server->egg->copy_script_entry,
            'script' => $server->egg->copy_script_install,
        ]);
    }

    /**
     * Updates the installation state of a server.
     *
     * @throws DataValidationException
     */
    public function store(InstallationDataRequest $request, Server $server): JsonResponse
    {
        $status = null;

        $successful = $request->boolean('successful');

        // Make sure the type of failure is accurate
        if (!$successful) {
            $status = $request->boolean('reinstall') ? ServerState::ReinstallFailed : ServerState::InstallFailed;
        }

        // Keep the server suspended if it's already suspended
        if ($server->status === ServerState::Suspended) {
            $status = ServerState::Suspended;
        }

        $previouslyInstalledAt = $server->installed_at;

        $server->status = $status;
        $server->installed_at = now();
        $server->save();

        $isInitialInstall = is_null($previouslyInstalledAt);
        event(new ServerInstalled($server, $successful, $isInitialInstall));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
