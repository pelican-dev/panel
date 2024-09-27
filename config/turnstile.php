<?php

return [

    'turnstile_enabled' => env('TURNSTILE_ENABLED', false),
    'turnstile_site_key' => env('TURNSTILE_SITE_KEY', null),
    'turnstile_secret_key' => env('TURNSTILE_SECRET_KEY', null),

    'error_messages' => [
        'turnstile_check_message' => 'Captcha failed! Please refresh and try again.',
    ],
];
