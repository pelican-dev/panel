<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Scramble::registerUiRoute(path: 'application', api: 'application');
    Scramble::registerJsonSpecificationRoute(path: 'application.json', api: 'application');

    Scramble::registerUiRoute(path: 'client', api: 'client');
    Scramble::registerJsonSpecificationRoute(path: 'client.json', api: 'client');

    Route::view('', 'docs.api-index');
});
