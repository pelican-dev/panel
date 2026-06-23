<?php

use App\Http\Middleware\AddPasskeyErrorMessage;

return [
    'middleware' => ['web', AddPasskeyErrorMessage::class],
    'management_middleware' => ['auth'],
];
