<?php

namespace App\Http\Middleware\Api\Application;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthenticateApplicationUser
{
    /**
     * Authenticate that the currently authenticated user is an administrator
     * and should be allowed to proceed through the application API.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var User|null $user */
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            throw new AccessDeniedHttpException('This account does not have permission to access the API.');
        }

        return $next($request);
    }
}
