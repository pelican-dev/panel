<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'github' => [
        'client_id' => env('OAUTH_GITHUB_CLIENT_ID'),
        'client_secret' => env('OAUTH_GITHUB_CLIENT_SECRET'),
        'redirect' => '/auth/oauth/callback/github',
    ],

    'discord' => [
        'client_id' => env('OAUTH_DISCORD_CLIENT_ID'),
        'client_secret' => env('OAUTH_DISCORD_CLIENT_SECRET'),
        'redirect' => '/auth/oauth/callback/discord',
    ],

];
