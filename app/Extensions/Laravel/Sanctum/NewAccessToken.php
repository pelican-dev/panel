<?php

namespace App\Extensions\Laravel\Sanctum;

use App\Models\ApiKey;
use Laravel\Sanctum\NewAccessToken as SanctumAccessToken;

/**
 * @property ApiKey $accessToken
 */
class NewAccessToken extends SanctumAccessToken
{
    /**
     * NewAccessToken constructor.
     *
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct(ApiKey $accessToken, string $plainTextToken)
    {
        $this->accessToken = $accessToken;
        $this->plainTextToken = $plainTextToken;
    }
}
