<?php

use Illuminate\Support\Str;

return [

    'stores' => [
        'sessions' => [
            'driver' => env('SESSION_DRIVER', 'database'),
            'table' => 'sessions',
            'connection' => env('SESSION_DRIVER') === 'redis' ? 'sessions' : null,
        ],
    ],

];
