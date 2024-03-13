<?php

namespace App\Http\Middleware\Activity;

use Illuminate\Http\Request;
use App\Facades\LogTarget;

class AccountSubject
{
    /**
     * Sets the actor and default subject for all requests passing through this
     * middleware to be the currently logged in user.
     */
    public function handle(Request $request, \Closure $next)
    {
        LogTarget::setActor($request->user());
        LogTarget::setSubject($request->user());

        return $next($request);
    }
}
