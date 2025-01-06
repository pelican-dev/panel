<?php

namespace App\Http\Middleware;

use App\Filament\App\Resources\ServerResource\Pages\ListServers;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;

class RedirectIfAuthenticated
{
    /**
     * RedirectIfAuthenticated constructor.
     */
    public function __construct(private AuthManager $authManager) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, \Closure $next, ?string $guard = null): mixed
    {
        if ($this->authManager->guard($guard)->check()) {
            return redirect(ListServers::getUrl());
        }

        return $next($request);
    }
}
