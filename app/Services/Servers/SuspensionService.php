<?php

namespace App\Services\Servers;

use App\Enums\ServerState;
use App\Enums\SuspendAction;
use App\Models\Server;
use App\Repositories\Daemon\DaemonServerRepository;
use Filament\Notifications\Notification;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Throwable;

class SuspensionService
{
    /**
     * SuspensionService constructor.
     */
    public function __construct(
        private DaemonServerRepository $daemonServerRepository
    ) {}

    /**
     * Suspends a server on the system.
     *
     * @throws Throwable
     */
    public function handle(Server $server, SuspendAction $action): void
    {
        $isSuspending = $action === SuspendAction::Suspend;
        // Nothing needs to happen if we're suspending the server, and it is already
        // suspended in the database. Additionally, nothing needs to happen if the server
        // is not suspended, and we try to un-suspend the instance.
        if ($isSuspending === $server->isSuspended()) {
            Notification::make()->danger()->title(trans('notifications.failed'))->body(trans('admin/server.notifications.server_already_suspended'))->send();

            return;
        }

        // Check if the server is currently being transferred.
        if (!is_null($server->transfer)) {
            Notification::make()->danger()->title(trans('notifications.failed'))->body(trans('admin/server.notifications.already_transfering'))->send();
            throw new ConflictHttpException('Cannot toggle suspension status on a server that is currently being transferred.');
        }

        // Update the server's suspension status.
        $server->update([
            'status' => $isSuspending ? ServerState::Suspended : null,
        ]);

        // Tell daemon to re-sync the server state.
        $this->daemonServerRepository->setServer($server)->sync();
    }
}
