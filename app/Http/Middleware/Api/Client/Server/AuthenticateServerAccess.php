<?php

namespace App\Http\Middleware\Api\Client\Server;

use Illuminate\Http\Request;
use App\Models\Server;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Exceptions\Http\Server\ServerStateConflictException;

class AuthenticateServerAccess
{
    /**
     * Routes that this middleware should not apply to if the user is an admin.
     */
    protected array $except = [
        'api:client:server.ws',
    ];

    /**
     * AuthenticateServerAccess constructor.
     */
    public function __construct() {}

    /**
     * Authenticate that this server exists and is not suspended or marked as installing.
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $server = $request->route()->parameter('server');

        if (!$server instanceof Server) {
            throw new NotFoundHttpException(trans('exceptions.api.resource_not_found'));
        }

        // At the very least, ensure that the user trying to make this request is the
        // server owner, a subuser, or a root admin. We'll leave it up to the controllers
        // to authenticate more detailed permissions if needed.
        if ($user->id !== $server->owner_id && !$user->isRootAdmin()) {
            // Check for subuser status.
            if (!$server->subusers->contains('user_id', $user->id)) {
                throw new NotFoundHttpException(trans('exceptions.api.resource_not_found'));
            }
        }

        try {
            $server->validateCurrentState();
        } catch (ServerStateConflictException $exception) {
            // Still allow users to get information about their server if it is installing or
            // being transferred.
            if (!$request->routeIs('api:client:server.view')) {
                if (($server->isSuspended() || $server->node->isUnderMaintenance()) && !$request->routeIs('api:client:server.resources')) {
                    throw $exception;
                }
                if (!$user->isRootAdmin() || !$request->routeIs($this->except)) {
                    throw $exception;
                }
            }
        }

        $request->attributes->set('server', $server);

        return $next($request);
    }
}
