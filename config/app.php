<?php

return [

    'name' => env('APP_NAME', 'Pelican'),
    'logo' => env('APP_LOGO', '/pelican.svg'),
    'favicon' => env('APP_FAVICON', '/pelican.ico'),

    'version' => 'canary',

    'timezone' => 'UTC',

    'exceptions' => [
        'report_all' => env('APP_REPORT_ALL_EXCEPTIONS', false),
    ],

];
