<?php

namespace App\Http\Middleware\Api\Application;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RequireApplicationApiKey
{
    /**
     * Blocks a request to the Application API endpoints if the user is providing an API token
     * that was created for the client API.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->user()->currentAccessToken();

        if ($token instanceof ApiKey && $token->key_type === ApiKey::TYPE_ACCOUNT) {
            throw new AccessDeniedHttpException('You are attempting to use a client API key on an endpoint that requires an application API key.');
        }

        return $next($request);
    }
}
