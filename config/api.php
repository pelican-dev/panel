<?php

return [
    'key' => [
        'limit' => env('API_KEYS_LIMIT', 25),
        'identifier_length' => env('API_KEYS_IDENTIFIER_LENGTH', 16),
        'secret_length' => env('API_KEYS_SECRET_LENGTH', 32),
    ],
];
