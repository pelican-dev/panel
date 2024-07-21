<?php

namespace App\Services\Servers;

use App\Enums\ServerState;
use Filament\Notifications\Notification;
use Webmozart\Assert\Assert;
use App\Models\Server;
use App\Repositories\Daemon\DaemonServerRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class SuspensionService
{
    public const ACTION_SUSPEND = 'suspend';
    public const ACTION_UNSUSPEND = 'unsuspend';

    /**
     * SuspensionService constructor.
     */
    public function __construct(
        private DaemonServerRepository $daemonServerRepository
    ) {
    }

    /**
     * Suspends a server on the system.
     *
     * @throws \Throwable
     */
    public function toggle(Server $server, string $action = self::ACTION_SUSPEND)
    {
        Assert::oneOf($action, [self::ACTION_SUSPEND, self::ACTION_UNSUSPEND]);

        $isSuspending = $action === self::ACTION_SUSPEND;
        // Nothing needs to happen if we're suspending the server, and it is already
        // suspended in the database. Additionally, nothing needs to happen if the server
        // is not suspended, and we try to un-suspend the instance.
        if ($isSuspending === $server->isSuspended()) {
            return Notification::make()->danger()->title('Failed!')->body('Server is already suspended!')->send();
        }

        // Check if the server is currently being transferred.
        if (!is_null($server->transfer)) {
            Notification::make()->danger()->title('Failed!')->body('Server is currently being transferred.')->send();
            throw new ConflictHttpException('Cannot toggle suspension status on a server that is currently being transferred.');
        }

        // Update the server's suspension status.
        $server->update([
            'status' => $isSuspending ? ServerState::Suspended : null,
        ]);

        try {
            // Tell daemon to re-sync the server state.
            $this->daemonServerRepository->setServer($server)->sync();
        } catch (\Exception $exception) {
            // Rollback the server's suspension status if daemon fails to sync the server.
            $server->update([
                'status' => $isSuspending ? null : ServerState::Suspended,
            ]);
            throw $exception;
        }
    }
}
