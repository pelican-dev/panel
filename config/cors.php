<?php

return [

    'paths' => ['/api/client', '/api/application', '/api/client/*', '/api/application/*'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'],

    'allowed_origins' => explode(',', env('APP_CORS_ALLOWED_ORIGINS') ?? ''),

    'supports_credentials' => true,

];
