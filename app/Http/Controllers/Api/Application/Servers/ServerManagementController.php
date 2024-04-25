<?php

namespace App\Http\Controllers\Api\Application\Servers;

use Illuminate\Http\Response;
use App\Models\Server;
use App\Services\Servers\SuspensionService;
use App\Services\Servers\ReinstallServerService;
use App\Services\Servers\TransferServerService;
use App\Http\Requests\Api\Application\Servers\ServerWriteRequest;
use App\Http\Controllers\Api\Application\ApplicationApiController;

class ServerManagementController extends ApplicationApiController
{
    /**
     * ServerManagementController constructor.
     */
    public function __construct(
        private ReinstallServerService $reinstallServerService,
        private SuspensionService $suspensionService,
        private TransferServerService $transferServerService,
    ) {
        parent::__construct();
    }

    /**
     * Suspend a server on the Panel.
     *
     * @throws \Throwable
     */
    public function suspend(ServerWriteRequest $request, Server $server): Response
    {
        $this->suspensionService->toggle($server);

        return $this->returnNoContent();
    }

    /**
     * Unsuspend a server on the Panel.
     *
     * @throws \Throwable
     */
    public function unsuspend(ServerWriteRequest $request, Server $server): Response
    {
        $this->suspensionService->toggle($server, SuspensionService::ACTION_UNSUSPEND);

        return $this->returnNoContent();
    }

    /**
     * Mark a server as needing to be reinstalled.
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function reinstall(ServerWriteRequest $request, Server $server): Response
    {
        $this->reinstallServerService->handle($server);

        return $this->returnNoContent();
    }

    /**
     * Starts a transfer of a server to a new node.
     */
    public function startTransfer(ServerWriteRequest $request, Server $server): Response
    {
        $validatedData = $request->validate([
            'node_id' => 'required|exists:nodes,id',
            'allocation_id' => 'required|bail|unique:servers|exists:allocations,id',
            'allocation_additional' => 'nullable',
        ]);

        if ($this->transferServerService->handle($server, $validatedData)) {
            // Transfer started
            $this->returnNoContent();
        } else {
            // Node was not viable
            return new Response('', Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * Cancels a transfer of a server to a new node.
     */
    public function cancelTransfer(ServerWriteRequest $request, Server $server): Response
    {
        if (!$transfer = $server->transfer) {
            // Server is not transferring
            return new Response('', Response::HTTP_NOT_ACCEPTABLE);
        }

        $transfer->successful = true;
        $transfer->save();

        // TODO: cancel transfer on wings
        //          on destination: https://github.com/pterodactyl/wings/blob/develop/router/router.go#L64
        //          on source: https://github.com/pterodactyl/wings/blob/develop/router/router.go#L85

        return $this->returnNoContent();
    }
}
