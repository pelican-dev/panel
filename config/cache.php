<?php

return [

    'default' => env('CACHE_STORE', env('CACHE_DRIVER', 'file')),

    'stores' => [
        'sessions' => [
            'driver' => env('SESSION_DRIVER', 'database'),
            'table' => 'sessions',
            'connection' => env('SESSION_DRIVER') === 'redis' ? 'sessions' : null,
        ],
    ],

];
