<?php

namespace App\Http\Middleware\Activity;

use Closure;
use App\Facades\LogTarget;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountSubject
{
    /**
     * Sets the actor and default subject for all requests passing through this
     * middleware to be the currently logged in user.
     */
    public function handle(Request $request, Closure $next): Response
    {
        LogTarget::setActor($request->user());
        LogTarget::setSubject($request->user());

        return $next($request);
    }
}
