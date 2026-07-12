<?php

namespace App\Data;

use App\Models\ApiKey;

class ApiKeyData extends Data
{
    public function __construct(
        public string $identifier,
        public ?string $description,
        public ?array $allowed_ips,
        public ?string $last_used_at,
        public string $created_at,
    ) {}

    public static function fromModel(ApiKey $model): self
    {
        return new self(
            identifier: $model->identifier,
            description: $model->memo,
            allowed_ips: $model->allowed_ips,
            last_used_at: $model->last_used_at?->toAtomString(),
            created_at: $model->created_at->toAtomString(),
        );
    }

    public function getResourceName(): string
    {
        return static::getResourceNameStatic();
    }

    public static function getResourceNameStatic(): string
    {
        return ApiKey::RESOURCE_NAME;
    }
}
