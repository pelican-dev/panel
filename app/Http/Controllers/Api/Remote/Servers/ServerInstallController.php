<?php

namespace App\Http\Controllers\Api\Remote\Servers;

use Illuminate\Http\Response;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Events\Server\Installed as ServerInstalled;
use App\Http\Requests\Api\Remote\InstallationDataRequest;

class ServerInstallController extends Controller
{
    /**
     * Returns installation information for a server.
     */
    public function index(Server $server): JsonResponse
    {
        $egg = $server->egg;

        return new JsonResponse([
            'container_image' => $egg->copy_script_container,
            'entrypoint' => $egg->copy_script_entry,
            'script' => $egg->copy_script_install,
        ]);
    }

    /**
     * Updates the installation state of a server.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function store(InstallationDataRequest $request, Server $server): JsonResponse
    {
        $status = null;

        // Make sure the type of failure is accurate
        if (!$request->boolean('successful')) {
            $status = Server::STATUS_INSTALL_FAILED;

            if ($request->boolean('reinstall')) {
                $status = Server::STATUS_REINSTALL_FAILED;
            }
        }

        // Keep the server suspended if it's already suspended
        if ($server->status === Server::STATUS_SUSPENDED) {
            $status = Server::STATUS_SUSPENDED;
        }

        $previouslyInstalledAt = $server->installed_at;

        $server->status = $status;
        $server->installed_at = now();
        $server->save();

        // If the server successfully installed, fire installed event.
        // This logic allows individually disabling install and reinstall notifications separately.
        $isInitialInstall = is_null($previouslyInstalledAt);
        if ($isInitialInstall && config()->get('panel.email.send_install_notification', true)) {
            event(new ServerInstalled($server));
        }

        if (!$isInitialInstall && config()->get('panel.email.send_reinstall_notification', true)) {
            event(new ServerInstalled($server));
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
