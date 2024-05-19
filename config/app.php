<?php

use Illuminate\Support\Facades\Facade;

return [

    'name' => env('APP_NAME', 'Pelican'),

    'version' => 'canary',

    'exceptions' => [
        'report_all' => env('APP_REPORT_ALL_EXCEPTIONS', false),
    ],

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
