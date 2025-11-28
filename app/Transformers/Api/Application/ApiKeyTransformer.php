<?php

namespace App\Transformers\Api\Application;

use App\Models\ApiKey;

class ApiKeyTransformer extends BaseTransformer
{
    public function getResourceName(): string
    {
        return ApiKey::RESOURCE_NAME;
    }

    /**
     * @param  ApiKey  $apiKey
     * @return array<string, mixed>
     */
    public function transform($apiKey): array
    {
        return [
            'id' => $apiKey->id,
            'user_id' => $apiKey->user_id,
            'key_type' => $apiKey->key_type,
            'identifier' => $apiKey->identifier,
            'memo' => $apiKey->memo,
            'allowed_ips' => $apiKey->allowed_ips,
            'permissions' => $apiKey->permissions,
            'last_used_at' => $apiKey->last_used_at?->toAtomString(),
            'expires_at' => $apiKey->expires_at?->toAtomString(),
            'created_at' => $apiKey->created_at->toAtomString(),
            'updated_at' => $apiKey->updated_at->toAtomString(),
        ];
    }
}
