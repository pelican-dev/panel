<?php

return [

    'auth' => [
        '2fa_required' => env('APP_2FA_REQUIRED', 0),
        '2fa' => [
            'bytes' => 32,
            'window' => env('APP_2FA_WINDOW', 4),
            'verify_newer' => true,
        ],
    ],

    'guzzle' => [
        'timeout' => env('GUZZLE_TIMEOUT', 15),
        'connect_timeout' => env('GUZZLE_CONNECT_TIMEOUT', 5),
    ],

    'cdn' => [
        'cache_time' => 60,
    ],

    'client_features' => [
        'databases' => [
            'enabled' => env('PANEL_CLIENT_DATABASES_ENABLED', true),
            'allow_random' => env('PANEL_CLIENT_DATABASES_ALLOW_RANDOM', true),
        ],

        'schedules' => [
            // The total number of tasks that can exist for any given schedule at once.
            'per_schedule_task_limit' => env('PANEL_PER_SCHEDULE_TASK_LIMIT', 10),
        ],

        'allocations' => [
            'enabled' => env('PANEL_CLIENT_ALLOCATIONS_ENABLED', false),
            'range_start' => env('PANEL_CLIENT_ALLOCATIONS_RANGE_START'),
            'range_end' => env('PANEL_CLIENT_ALLOCATIONS_RANGE_END'),
        ],
    ],

    'files' => [
        'max_edit_size' => env('PANEL_FILES_MAX_EDIT_SIZE', 1024 * 1024 * 4),
    ],

    'email' => [
        // Should an email be sent to a server owner once their server has completed it's first install process?
        'send_install_notification' => env('PANEL_SEND_INSTALL_NOTIFICATION', true),
        // Should an email be sent to a server owner whenever their server is reinstalled?
        'send_reinstall_notification' => env('PANEL_SEND_REINSTALL_NOTIFICATION', true),
    ],

    'filament' => [
        'top-navigation' => env('FILAMENT_TOP_NAVIGATION', false),
        'display-width' => env('FILAMENT_WIDTH', 'screen-2xl'),
        'avatar-provider' => env('FILAMENT_AVATAR_PROVIDER', 'gravatar'),
        'uploadable-avatars' => env('FILAMENT_UPLOADABLE_AVATARS', false),
    ],

    'use_binary_prefix' => env('PANEL_USE_BINARY_PREFIX', true),

    'editable_server_descriptions' => env('PANEL_EDITABLE_SERVER_DESCRIPTIONS', true),

    'api' => [
        'key_limit' => env('API_KEYS_LIMIT', 25),
        'key_expire_time' => env('API_KEYS_EXPIRE_TIME', 720),
    ],

    'webhook' => [
        'prune_days' => env('APP_WEBHOOK_PRUNE_DAYS', 30),
    ],
];
