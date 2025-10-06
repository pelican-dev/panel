<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

readonly class RedirectIfAuthenticated
{
    public function __construct(private AuthManager $authManager) {}

    public function handle(Request $request, Closure $next, ?string $guard = null): mixed
    {
        if ($this->authManager->guard($guard)->check()) {
            return redirect('/');
        }

        return $next($request);
    }
}
