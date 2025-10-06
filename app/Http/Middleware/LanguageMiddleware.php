<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    /**
     * LanguageMiddleware constructor.
     */
    public function __construct(private Application $app) {}

    /**
     * Handle an incoming request and set the user's preferred language.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $this->app->setLocale($request->user()->language ?? config('app.locale', 'en'));

        return $next($request);
    }
}
