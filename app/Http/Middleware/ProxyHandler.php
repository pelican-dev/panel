<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class ProxyHandler
{
    /**
     * Handle an incoming request and set the request IP to X-Forwarded-For if it exists.
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($request->hasHeader('X-Forwarded-For')) {
            $forwarded = $request->header('X-Forwarded-For');
            $client = trim(explode(',', $forwarded)[0]);
            $request->server->set('REMOTE_ADDR', $client);
        }

        return $next($request);
    }
}
