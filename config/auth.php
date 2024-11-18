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
        // Default providers
        'facebook' => [
            'enabled' => true,
            'icon' => 'tabler-brand-facebook',
            'color' => \Filament\Support\Colors\Color::hex('#1877f2'),
            'service' => [
                'client_id' => env('OAUTH_FACEBOOK_CLIENT_ID'),
                'client_secret' => env('OAUTH_FACEBOOK_CLIENT_SECRET'),
            ],
        ],
        'x' => [
            'enabled' => true,
            'icon' => 'tabler-brand-x',
            'color' => \Filament\Support\Colors\Color::hex('#1da1f2'),
            'service' => [
                'client_id' => env('OAUTH_X_CLIENT_ID'),
                'client_secret' => env('OAUTH_X_CLIENT_SECRET'),
            ],
        ],
        'linkedin' => [
            'enabled' => true,
            'icon' => 'tabler-brand-linkedin',
            'color' => \Filament\Support\Colors\Color::hex('#0a66c2'),
            'service' => [
                'client_id' => env('OAUTH_LINKEDIN_CLIENT_ID'),
                'client_secret' => env('OAUTH_LINKEDIN_CLIENT_SECRET'),
            ],
        ],
        'google' => [
            'enabled' => true,
            'icon' => 'tabler-brand-google',
            'color' => \Filament\Support\Colors\Color::hex('#4285f4'),
            'service' => [
                'client_id' => env('OAUTH_GOOGLE_CLIENT_ID'),
                'client_secret' => env('OAUTH_GOOGLE_CLIENT_SECRET'),
            ],
        ],
        'github' => [
            'enabled' => true,
            'icon' => 'tabler-brand-github',
            'color' => \Filament\Support\Colors\Color::hex('#4078c0'),
            'service' => [
                'client_id' => env('OAUTH_GITHUB_CLIENT_ID'),
                'client_secret' => env('OAUTH_GITHUB_CLIENT_SECRET'),
            ],
        ],
        'gitlab' => [
            'enabled' => true,
            'icon' => 'tabler-brand-gitlab',
            'color' => \Filament\Support\Colors\Color::hex('#fca326'),
            'service' => [
                'client_id' => env('OAUTH_GITLAB_CLIENT_ID'),
                'client_secret' => env('OAUTH_GITLAB_CLIENT_SECRET'),
            ],
        ],
        'bitbucket' => [
            'enabled' => true,
            'icon' => 'tabler-brand-bitbucket',
            'color' => \Filament\Support\Colors\Color::hex('#205081'),
            'service' => [
                'client_id' => env('OAUTH_BITBUCKET_CLIENT_ID'),
                'client_secret' => env('OAUTH_BITBUCKET_CLIENT_SECRET'),
            ],
        ],
        'slack' => [
            'enabled' => true,
            'icon' => 'tabler-brand-slack',
            'color' => \Filament\Support\Colors\Color::hex('#6ecadc'),
            'service' => [
                'client_id' => env('OAUTH_SLACK_CLIENT_ID'),
                'client_secret' => env('OAUTH_SLACK_CLIENT_SECRET'),
            ],
        ],

        // Additional providers from socialiteproviders.com
        'authentik' => [
            'enabled' => true,
            'icon' => null,
            'color' => \Filament\Support\Colors\Color::hex('#fd4b2d'),
            'service' => [
                'base_url' => env('OAUTH_AUTHENTIK_BASE_URL'),
                'client_id' => env('OAUTH_AUTHENTIK_CLIENT_ID'),
                'client_secret' => env('OAUTH_AUTHENTIK_CLIENT_SECRET'),
            ],
            'provider' => \SocialiteProviders\Authentik\Provider::class,
        ],
        'discord' => [
            'enabled' => true,
            'icon' => 'tabler-brand-discord',
            'color' => \Filament\Support\Colors\Color::hex('#5865F2'),
            'service' => [
                'client_id' => env('OAUTH_DISCORD_CLIENT_ID'),
                'client_secret' => env('OAUTH_DISCORD_CLIENT_SECRET'),
            ],
            'provider' => \SocialiteProviders\Discord\Provider::class,
        ],
        'steam' => [
            'enabled' => true,
            'icon' => 'tabler-brand-steam',
            'color' => \Filament\Support\Colors\Color::hex('#00adee'),
            'service' => [
                'client_secret' => env('OAUTH_STEAM_CLIENT_SECRET'),
                'allowed_hosts' => [
                    env('APP_URL'),
                ],
            ],
            'provider' => \SocialiteProviders\Steam\Provider::class,
        ],
        'whmcs' => [
            'enabled' => true,
            'icon' => null,
            'color' => \Filament\Support\Colors\Color::hex('#7bc143'),
            'service' => [
                'client_id' => env('OAUTH_WHMCS_CLIENT_ID'),
                'client_secret' => env('OAUTH_WHMCS_CLIENT_SECRET'),
                'url' => env('OAUTH_WHMCS_URL'),
            ],
            'provider' => \SocialiteProviders\Whmcs\Provider::class,
        ],
    ],

];
