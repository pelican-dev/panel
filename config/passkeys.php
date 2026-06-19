<?php

return [
    /*
     * The relying party ID used for WebAuthn. Defaults to the host of APP_URL.
     */
    'relying_party_id' => parse_url(config('app.url'), PHP_URL_HOST),

    /*
     * Origins that are allowed to use passkeys. Defaults to APP_URL.
     */
    'allowed_origins' => [config('app.url')],

    /*
     * Secret used to generate user handles. Defaults to APP_KEY.
     */
    'user_handle_secret' => env('PASSKEYS_USER_HANDLE_SECRET', config('app.key')),

    /*
     * Timeout in milliseconds for WebAuthn operations.
     */
    'timeout' => 60000,

    /*
     * The authentication guard to use.
     */
    'guard' => 'web',

    /*
     * Middleware applied to all passkey routes.
     */
    'middleware' => ['web'],

    /*
     * Middleware applied to passkey management routes (register, delete).
     * Using 'auth' instead of 'password.confirm' to match existing behavior.
     */
    'management_middleware' => ['auth'],

    /*
     * Throttle middleware applied to login routes.
     */
    'throttle' => 'throttle:6,1',

    /*
     * URL to redirect to after successful passkey authentication.
     */
    'redirect' => '/',
];
