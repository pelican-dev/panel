<?php

namespace App\Exceptions\Http\Server;

use App\Enums\ServerState;
use App\Models\Server;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ServerStateConflictException extends ConflictHttpException
{
    /**
     * Exception thrown when the server is in an unsupported state for API access or
     * certain operations within the codebase.
     */
    public function __construct(Server $server, ?\Throwable $previous = null)
    {
        $message = match (true) {
            $server->transfer !== null => 'This server is currently being transferred to a new machine, please try again later.',
            $server->isSuspended() => 'This server is currently suspended and the functionality requested is unavailable.',
            $server->node->isUnderMaintenance() => 'The node of this server is currently under maintenance and the functionality requested is unavailable.',
            $server->status === ServerState::InstallFailed => 'The server installation has failed.',
            $server->status === ServerState::Installing => 'This server has not yet completed its installation process, please try again later.',
            $server->status === ServerState::RestoringBackup => 'This server is currently restoring from a backup, please try again later.',
            default => 'This server is currently in an unsupported state, please try again later.',
        };

        parent::__construct($message, $previous);
    }
}
