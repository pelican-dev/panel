<?php

return [

    'lockout' => [
        'time' => 2,
        'attempts' => 3,
    ],

    'guards' => [
        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],

];
