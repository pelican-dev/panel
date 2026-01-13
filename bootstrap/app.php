<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('filament.app.auth.login'));

        $middleware->web(\App\Http\Middleware\LanguageMiddleware::class);

        $middleware->api([
            \App\Http\Middleware\EnsureStatefulRequests::class,
            'auth:sanctum',
            \App\Http\Middleware\Api\IsValidJson::class,
            \App\Http\Middleware\Activity\TrackAPIKey::class,
            \App\Http\Middleware\Api\AuthenticateIPAccess::class,
        ]);

        $middleware->group('application-api', [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Api\Application\AuthenticateApplicationUser::class,
        ]);

        $middleware->group('client-api', [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Api\Client\RequireClientApiKey::class,
        ]);

        $middleware->group('daemon', [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Api\Daemon\DaemonAuthenticate::class,
        ]);

        $middleware->replaceInGroup('web', \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class, \App\Http\Middleware\VerifyCsrfToken::class);

        $middleware->alias([
            'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'node.maintenance' => \App\Http\Middleware\MaintenanceMiddleware::class,
        ]);

        $middleware->priority([
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withSingletons([
        \Illuminate\Contracts\Console\Kernel::class => \App\Console\Kernel::class,
        \Illuminate\Contracts\Debug\ExceptionHandler::class => \App\Exceptions\Handler::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
