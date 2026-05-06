<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as BaseMiddleware;

class PreventRequestForgery extends BaseMiddleware
{
    /**
     * The URIs that should be excluded from CSRF verification. These are
     * never hit by the front-end, and require specific token validation
     * to work.
     */
    protected $except = ['remote/*', 'daemon/*'];
}
