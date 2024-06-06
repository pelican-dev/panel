<?php

namespace App\Http\Controllers\Api\Application\Servers;

use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Servers\ServerWriteRequest;
use App\Models\Server;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Services\Servers\ReinstallServerService;
use App\Services\Servers\SuspensionService;
use App\Services\Servers\TransferServerService;
use Illuminate\Http\Response;

class ServerManagementController extends ApplicationApiController
{
    /**
     * ServerManagementController constructor.
     */
    public function __construct(
        private ReinstallServerService $reinstallServerService,
        private SuspensionService $suspensionService,
        private TransferServerService $transferServerService,
        private DaemonServerRepository $daemonServerRepository,
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
            return $this->returnNoContent();
        }

        // Node was not viable
        return new Response('', Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Cancels a transfer of a server to a new node.
     *
     * @throws \App\Exceptions\Http\Connection\DaemonConnectionException
     */
    public function cancelTransfer(ServerWriteRequest $request, Server $server): Response
    {
        if (!$transfer = $server->transfer) {
            // Server is not transferring
            return new Response('', Response::HTTP_NOT_ACCEPTABLE);
        }

        $transfer->successful = true;
        $transfer->save();

        $this->daemonServerRepository->setServer($server)->cancelTransfer();

        return $this->returnNoContent();
    }
}
