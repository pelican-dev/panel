<?php

namespace App\Models\Traits;

use App\Extensions\Laravel\Sanctum\NewAccessToken;
use App\Models\ApiKey;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;

/**
 * @mixin \App\Models\Model
 */
trait HasAccessTokens
{
    use HasApiTokens {
        tokens as private _tokens;
        createToken as private _createToken;
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(Sanctum::$personalAccessTokenModel);
    }

    /**
     * @param  ?string[]  $ips
     */
    public function createToken(?string $memo, ?array $ips): NewAccessToken
    {
        /** @var ApiKey $token */
        $token = $this->tokens()->forceCreate([
            'user_id' => $this->id,
            'key_type' => ApiKey::TYPE_ACCOUNT,
            'identifier' => ApiKey::generateTokenIdentifier(ApiKey::TYPE_ACCOUNT),
            'token' => $plain = Str::random(ApiKey::KEY_LENGTH),
            'memo' => $memo ?? '',
            'allowed_ips' => $ips ?? [],
        ]);

        return new NewAccessToken($token, $plain);
    }
}
