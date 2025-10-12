<?php

namespace App\Http\Middleware\Activity;

use App\Facades\LogTarget;
use App\Models\Server;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerSubject
{
    /**
     * Attempts to automatically scope all the activity log events registered
     * within the request instance to the given user and server. This only sets
     * the actor and subject if there is a server present on the request.
     *
     * If no server is found this is a no-op as the activity log service can always
     * set the user based on the authmanager response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $server = $request->route()->parameter('server');
        $server ??= Filament::getTenant();

        if ($server instanceof Server) {
            LogTarget::setActor($request->user());
            LogTarget::setSubject($server);
        }

        return $next($request);
    }
}
