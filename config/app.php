<?php

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;

return [

    'version' => 'canary',

    'exceptions' => [
        'report_all' => env('APP_REPORT_ALL_EXCEPTIONS', false),
    ],

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Laravel Framework Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\ActivityLogServiceProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BackupsServiceProvider::class,
        App\Providers\BladeServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\HashidsServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\ViewComposerServiceProvider::class,

        /*
         * Additional Dependencies
         */
        Prologue\Alerts\AlertsServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        'Alert' => Prologue\Alerts\Facades\Alert::class,
        'Carbon' => Carbon\Carbon::class,
        'JavaScript' => Laracasts\Utilities\JavaScript\JavaScriptFacade::class,
        'Theme' => App\Extensions\Facades\Theme::class,

        // Custom Facades
        'Activity' => App\Facades\Activity::class,
        'LogBatch' => App\Facades\LogBatch::class,
        'LogTarget' => App\Facades\LogTarget::class,
    ])->toArray(),

];
