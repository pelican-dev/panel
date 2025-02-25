<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {
    Scramble::registerUiRoute(path: 'application', api: 'application');
    Scramble::registerJsonSpecificationRoute(path: 'application.json', api: 'application');

    Scramble::registerUiRoute(path: 'client', api: 'client');
    Scramble::registerJsonSpecificationRoute(path: 'client.json', api: 'client');

    Route::get('', fn () => '
        <h2>API Docs</h2>
        <li><a href="/docs/api/application">Application API</a></li>
        <li><a href="/docs/api/client">Client API</a></li>
        <p>Note: You need to be logged in to view the api docs!</p>
    ');
});
