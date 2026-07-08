<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.installed')) {
            return $next($request);
        }

        if ($request->is('installer', 'installer/*', 'livewire/*', 'up')) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['error' => 'Installation incomplete.'], 503);
        }

        return redirect()->route('installer');
    }
}
