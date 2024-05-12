<?php

use Illuminate\Support\Facades\Facade;

return [

    'name' => 'Pelican',

    'version' => '1.0.0-beta1',

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
