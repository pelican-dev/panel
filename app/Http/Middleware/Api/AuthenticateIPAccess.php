<?php

namespace App\Http\Middleware\Api;

use App\Facades\Activity;
use App\Models\ApiKey;
use Closure;
use Exception;
use Illuminate\Http\Request;
use IPTools\IP;
use IPTools\Range;
use Laravel\Sanctum\TransientToken;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthenticateIPAccess
{
    /**
     * Determine if a request IP has permission to access the API.
     *
     * @throws Exception
     * @throws AccessDeniedHttpException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var TransientToken|ApiKey $token */
        $token = $request->user()->currentAccessToken();

        // If this is a stateful request just push the request through to the next
        // middleware in the stack, there is nothing we need to explicitly check. If
        // this is a valid API Key, but there is no allowed IP restriction, also pass
        // the request through.
        if ($token instanceof TransientToken || empty($token->allowed_ips)) {
            return $next($request);
        }

        $find = new IP($request->ip());
        foreach ($token->allowed_ips as $ip) {
            if (Range::parse($ip)->contains($find)) {
                return $next($request);
            }
        }

        Activity::event('auth:ip-blocked')
            ->actor($request->user())
            ->subject($request->user(), $token)
            ->property('identifier', $token->identifier)
            ->log();

        throw new AccessDeniedHttpException('This IP address (' . $request->ip() . ') does not have permission to access the API using these credentials.');
    }
}
