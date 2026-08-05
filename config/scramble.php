<?php

use App\Extensions\Dedoc\Scramble\ApiRequestDocumentationExtension;

return array_replace_recursive(
    require __DIR__.'/../vendor/dedoc/scramble/config/scramble.php',
    [
        'extensions' => [
            ApiRequestDocumentationExtension::class,
        ],
    ],
);
