<?php

use Filament\Support\Colors\Color;

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
        // Default providers
        'facebook' => [
            'enabled' => env('OAUTH_FACEBOOK_ENABLED'),
            'icon' => 'tabler-brand-facebook',
            'color' => Color::hex('#1877f2'),
            'service' => [
                'client_id' => env('OAUTH_FACEBOOK_CLIENT_ID'),
                'client_secret' => env('OAUTH_FACEBOOK_CLIENT_SECRET'),
            ],
        ],
        'x' => [
            'enabled' => env('OAUTH_X_ENABLED'),
            'icon' => 'tabler-brand-x',
            'color' => Color::hex('#1da1f2'),
            'service' => [
                'client_id' => env('OAUTH_X_CLIENT_ID'),
                'client_secret' => env('OAUTH_X_CLIENT_SECRET'),
            ],
        ],
        'linkedin' => [
            'enabled' => env('OAUTH_LINKEDIN_ENABLED'),
            'icon' => 'tabler-brand-linkedin',
            'color' => Color::hex('#0a66c2'),
            'service' => [
                'client_id' => env('OAUTH_LINKEDIN_CLIENT_ID'),
                'client_secret' => env('OAUTH_LINKEDIN_CLIENT_SECRET'),
            ],
        ],
        'google' => [
            'enabled' => env('OAUTH_GOOGLE_ENABLED'),
            'icon' => 'tabler-brand-google',
            'color' => Color::hex('#4285f4'),
            'service' => [
                'client_id' => env('OAUTH_GOOGLE_CLIENT_ID'),
                'client_secret' => env('OAUTH_GOOGLE_CLIENT_SECRET'),
            ],
        ],
        'github' => [
            'enabled' => env('OAUTH_GITHUB_ENABLED'),
            'icon' => 'tabler-brand-github',
            'color' => Color::hex('#4078c0'),
            'service' => [
                'client_id' => env('OAUTH_GITHUB_CLIENT_ID'),
                'client_secret' => env('OAUTH_GITHUB_CLIENT_SECRET'),
            ],
        ],
        'gitlab' => [
            'enabled' => env('OAUTH_GITLAB_ENABLED'),
            'icon' => 'tabler-brand-gitlab',
            'color' => Color::hex('#fca326'),
            'service' => [
                'client_id' => env('OAUTH_GITLAB_CLIENT_ID'),
                'client_secret' => env('OAUTH_GITLAB_CLIENT_SECRET'),
            ],
        ],
        'bitbucket' => [
            'enabled' => env('OAUTH_BITBUCKET_ENABLED'),
            'icon' => 'tabler-brand-bitbucket',
            'color' => Color::hex('#205081'),
            'service' => [
                'client_id' => env('OAUTH_BITBUCKET_CLIENT_ID'),
                'client_secret' => env('OAUTH_BITBUCKET_CLIENT_SECRET'),
            ],
        ],
        'slack' => [
            'enabled' => env('OAUTH_SLACK_ENABLED'),
            'icon' => 'tabler-brand-slack',
            'color' => Color::hex('#6ecadc'),
            'service' => [
                'client_id' => env('OAUTH_SLACK_CLIENT_ID'),
                'client_secret' => env('OAUTH_SLACK_CLIENT_SECRET'),
            ],
        ],

        // Additional providers from socialiteproviders.com
        'authentik' => [
            'enabled' => env('OAUTH_AUTHENTIK_ENABLED'),
            'icon' => null,
            'color' => Color::hex('#fd4b2d'),
            'service' => [
                'base_url' => env('OAUTH_AUTHENTIK_BASE_URL'),
                'client_id' => env('OAUTH_AUTHENTIK_CLIENT_ID'),
                'client_secret' => env('OAUTH_AUTHENTIK_CLIENT_SECRET'),
            ],
            'provider' => \SocialiteProviders\Authentik\Provider::class,
        ],
        'discord' => [
            'enabled' => env('OAUTH_DISCORD_ENABLED'),
            'icon' => 'tabler-brand-discord',
            'color' => Color::hex('#5865F2'),
            'service' => [
                'client_id' => env('OAUTH_DISCORD_CLIENT_ID'),
                'client_secret' => env('OAUTH_DISCORD_CLIENT_SECRET'),
            ],
            'provider' => \SocialiteProviders\Discord\Provider::class,
        ],
        'steam' => [
            'enabled' => env('OAUTH_STEAM_ENABLED'),
            'icon' => 'tabler-brand-steam',
            'color' => Color::hex('#00adee'),
            'service' => [
                'client_secret' => env('OAUTH_STEAM_CLIENT_SECRET'),
                'allowed_hosts' => [
                    env('APP_URL'),
                ],
            ],
            'provider' => \SocialiteProviders\Steam\Provider::class,
        ],
    ],

];
