<?php

declare(strict_types=1);

return [

    /* -----------------------------------------------------------------
    | Driver
    | -----------------------------------------------------------------
    | Available drivers: 'daily', 'stack', 'raw'
    | -----------------------------------------------------------------
     */

    'driver' => env('FILAMENT_LOG_VIEWER_DRIVER', env('LOG_CHANNEL', 'stack')),

    /* -----------------------------------------------------------------
    | Resource configuration
    | -----------------------------------------------------------------
     */

    'resource' => [
        'slug' => 'logs',
        'cluster' => null,
    ],

    /* -----------------------------------------------------------------
    | Logs files can be cleared
    | -----------------------------------------------------------------
    */

    'clearable' => env('FILAMENT_LOG_VIEWER_CLEARABLE', false),

    /* -----------------------------------------------------------------
    |  Log files storage path
    | -----------------------------------------------------------------
     */

    'storage_path' => storage_path('logs'),

    /* -----------------------------------------------------------------
    |  Log files pattern
    | -----------------------------------------------------------------
     */

    'pattern' => [
        'prefix' => 'laravel-',
        'date' => '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]',
        'extension' => '.log',
    ],

    /* -----------------------------------------------------------------
    |  Log entries per page
    | -----------------------------------------------------------------
    |  This defines how many logs and entries are displayed per page.
     */

    'per-page' => [
        5,
        10,
        25,
        30,
    ],

    /* -----------------------------------------------------------------
    |  Download settings
    | -----------------------------------------------------------------
     */

    'download' => [
        'prefix' => 'laravel-',

        'extension' => 'log',
    ],

    /* -----------------------------------------------------------------
    |  Icons
    | -----------------------------------------------------------------
     */

    'icons' => [
        'all' => 'fas-list', // http://fontawesome.io/icon/list/
        'emergency' => 'fas-bug', // http://fontawesome.io/icon/bug/
        'alert' => 'fas-bullhorn', // http://fontawesome.io/icon/bullhorn/
        'critical' => 'fas-heartbeat', // http://fontawesome.io/icon/heartbeat/
        'error' => 'fas-times-circle', // http://fontawesome.io/icon/times-circle/
        'warning' => 'fas-exclamation-triangle', // http://fontawesome.io/icon/exclamation-triangle/
        'notice' => 'fas-exclamation-circle', // http://fontawesome.io/icon/exclamation-circle/
        'info' => 'fas-info-circle', // http://fontawesome.io/icon/info-circle/
        'debug' => 'fas-life-ring', // http://fontawesome.io/icon/life-ring/
    ],

    /* -----------------------------------------------------------------
    |  Colors
    | -----------------------------------------------------------------
     */

    'colors' => [
        'levels' => [
            'all' => '#5C6878',
            'emergency' => '#8C2D2D',
            'alert' => '#B43C3C',
            'critical' => '#B43C3C',
            'error' => '#B43C3C',
            'warning' => '#D89020',
            'notice' => '#5C6878',
            'info' => '#5C6878',
            'debug' => '#5C6878',
        ],
    ],

    /* -----------------------------------------------------------------
    |  Strings to highlight in stack trace
    | -----------------------------------------------------------------
     */

    'highlight' => [
        '^#\d+', '^Stack trace:',
    ],
];
