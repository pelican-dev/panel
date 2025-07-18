<?php

return [

    'name' => env('APP_NAME', 'Pelican'),
    'logo' => env('APP_LOGO'),
    'favicon' => env('APP_FAVICON', '/pelican.ico'),

    'version' => 'canary',

    'timezone' => 'UTC',

    'installed' => env('APP_INSTALLED', true),

    'exceptions' => [
        'report_all' => env('APP_REPORT_ALL_EXCEPTIONS', false),
    ],

    'fallback_locale' => 'en',

];
