<?php

return [

    'turnstile_enabled' => env('TURNSTILE_ENABLED', false),

    'turnstile_site_key' => env('TURNSTILE_SITE_KEY', null),
    'turnstile_secret_key' => env('TURNSTILE_SECRET_KEY', null),

    'turnstile_verify_domain' => env('TURNSTILE_VERIFY_DOMAIN', true),

    'error_messages' => [
        'turnstile_check_message' => 'Captcha failed! Please refresh and try again.',
    ],
];
