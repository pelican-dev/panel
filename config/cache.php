<?php

return [

    'default' => env('CACHE_STORE', env('CACHE_DRIVER', 'file')),

    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION', 'default'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],
    ],

];
