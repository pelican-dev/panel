<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Scramble::registerUiRoute(path: 'application', api: 'application');
    Scramble::registerJsonSpecificationRoute(path: 'application.json', api: 'application');

    Scramble::registerUiRoute(path: 'client', api: 'client');
    Scramble::registerJsonSpecificationRoute(path: 'client.json', api: 'client');

    Scramble::registerUiRoute(path: 'remote', api: 'remote');
    Scramble::registerJsonSpecificationRoute(path: 'remote.json', api: 'remote');

    Route::get('', fn () => '
        <li><a href="/docs/api/application">Application API for Admins</a></li>
        <li><a href="/docs/api/client">Client API for Users</a></li>
        <li><a href="/docs/api/remote">Daemon API for Wings</a></li>
    ');
});
