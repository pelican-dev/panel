<?php

use Dedoc\Scramble\Http\Middleware\RestrictedDocsAccess;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Scramble::extendOpenApi(fn (OpenApi $openApi) => $openApi->secure(SecurityScheme::http('bearer')));

    Route::view('application', 'scramble::docs', ['api' => 'application'])->name('scramble.docs.api.application');
    Route::view('client', 'scramble::docs', ['api' => 'client'])->name('scramble.docs.api.client');
    Route::view('remote', 'scramble::docs', ['api' => 'remote'])->name('scramble.docs.api.remote');

    Route::get('application.json', function (Dedoc\Scramble\Generator $generator) {
        config()->set('scramble.api_path', 'api/application');
        config()->set('scramble.info.description', '
            These are the Application API endpoints for admins.
            They let you interact with your Panel on a root basis.
        ');

        return $generator();
    })->name('scramble.docs.application');

    Route::get('client.json', function (Dedoc\Scramble\Generator $generator) {
        config()->set('scramble.api_path', 'api/client');
        config()->set('scramble.info.description', '
            These are the Client API endpoints for individual users.
            They let your users interact with your Panel.
        ');

        return $generator();
    })->name('scramble.docs.client');

    Route::get('remote.json', function (Dedoc\Scramble\Generator $generator) {
        config()->set('scramble.api_path', 'api/remote');
        config()->set('scramble.info.description', '
            These are the Remote API endpoints for Wings.
            They let Wings interact with your Panel.
        ');

        return $generator();
    })->name('scramble.docs.remote');

    Route::get('', fn () => '
        <li><a href="/docs/api/application">Application API for Admins</a></li>
        <li><a href="/docs/api/client">Client API for Users</a></li>
        <li><a href="/docs/api/remote">Daemon API for Wings</a></li>
    ');
})->middleware(config('scramble.middleware', [RestrictedDocsAccess::class]));
