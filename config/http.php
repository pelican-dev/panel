<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Rate Limits
    |--------------------------------------------------------------------------
    |
    | Defines the rate limit for the number of requests that can be
    | executed against the client and internal (application) APIs along with
    | certain other endpoints over a defined period (1 minute for most)
    */
    'rate_limit' => [
        'client_period' => env('APP_API_CLIENT_RATELIMIT_PERIOD', 1),
        'client' => env('APP_API_CLIENT_RATELIMIT', 256),

        'application_period' => env('APP_API_APPLICATION_RATELIMIT_PERIOD', 1),
        'application' => env('APP_API_APPLICATION_RATELIMIT', 256),

        'auth_period' => env('APP_API_AUTH_RATELIMIT_PERIOD', 1),
        'auth' => env('APP_API_AUTH_RATELIMIT', 10),

        'password_reset_period' => env('APP_API_PASSWORD_RESET_RATELIMIT_PERIOD', 1),
        'password_reset' => env('APP_API_PASSWORD_RESET_RATELIMIT', 2),

        'websocket_period' => env('APP_API_WEBSOCKET_RATELIMIT_PERIOD', 1),
        'websocket' => env('APP_API_WEBSOCKET_RATELIMIT', 5),

        'backup_restore_period' => env('APP_API_BACKUP_RESTORE_RATELIMIT_PERIOD', 15),
        'backup_restore' => env('APP_API_BACKUP_RESTORE_RATELIMIT', 3),

        'database_create_period' => env('APP_API_DATABASE_CREATE_RATELIMIT_PERIOD', 1),
        'database_create' => env('APP_API_DATABASE_CREATE_RATELIMIT', 2),

        'subuser_create_period' => env('APP_API_SUBUSER_CREATE_RATELIMIT_PERIOD', 15),
        'subuser_create' => env('APP_API_SUBUSER_CREATE_RATELIMIT', 10),

        'file_pull_period' => env('APP_API_FILE_PULL_RATELIMIT_PERIOD', 10),
        'file_pull' => env('APP_API_FILE_PULL_RATELIMIT', 5),

        'default_period' => env('APP_API_DEFAULT_RATELIMIT_PERIOD', 1),
        'default' => env('APP_API_DEFAULT_RATELIMIT', 2),
    ],
];
