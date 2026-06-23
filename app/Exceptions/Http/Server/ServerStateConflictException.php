<?php

namespace App\Exceptions\Http\Server;

use App\Enums\ServerState;
use App\Models\Server;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Throwable;

class ServerStateConflictException extends ConflictHttpException
{
    /**
     * Exception thrown when the server is in an unsupported state for API access or
     * certain operations within the codebase.
     */
    public function __construct(Server $server, ?Throwable $previous = null)
    {
        $message = trans('exceptions.server.state_conflict');
        if ($server->isSuspended()) {
            $message = trans('exceptions.server.suspended');
        } elseif ($server->node->isUnderMaintenance()) {
            $message = trans('exceptions.server.maintenance');
        } elseif (!$server->isInstalled()) {
            $message = trans('exceptions.server.marked_as_failed');
        } elseif ($server->status === ServerState::RestoringBackup) {
            $message = trans('exceptions.server.restoring_backup');
        } elseif (!is_null($server->transfer)) {
            $message = trans('exceptions.server.transferring');
        }

        parent::__construct($message, $previous);
    }
}
