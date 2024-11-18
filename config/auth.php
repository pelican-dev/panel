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

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'oauth' => [
        'github' => [
            'enabled' => true,
            'icon' => 'tabler-brand-github',
            'color' => \Filament\Support\Colors\Color::hex('#4078c0'),
            'client_id' => env('OAUTH_GITHUB_CLIENT_ID'),
            'client_secret' => env('OAUTH_GITHUB_CLIENT_SECRET'),
        ],
        'discord' => [
            'enabled' => true,
            'icon' => 'tabler-brand-discord',
            'color' => \Filament\Support\Colors\Color::hex('#5865F2'),
            'client_id' => env('OAUTH_DISCORD_CLIENT_ID'),
            'client_secret' => env('OAUTH_DISCORD_CLIENT_SECRET'),
            'provider' => \SocialiteProviders\Discord\Provider::class,
        ],
    ],

];
