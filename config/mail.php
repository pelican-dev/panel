<?php

return [

    'default' => env('MAIL_MAILER', 'log'),

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Pelican Admin'),
    ],

    'mailers' => [
        'mailgun' => [
            'transport' => 'mailgun',
        ],
    ],

];
