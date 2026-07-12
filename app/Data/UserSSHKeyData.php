<?php

namespace App\Data;

use App\Models\UserSSHKey;

class UserSSHKeyData extends Data
{
    public function __construct(
        public string $name,
        public string $fingerprint,
        public string $public_key,
        public string $created_at,
    ) {}

    public static function fromModel(UserSSHKey $model): self
    {
        return new self(
            name: $model->name,
            fingerprint: $model->fingerprint,
            public_key: $model->public_key,
            created_at: $model->created_at->toAtomString(),
        );
    }

    public function getResourceName(): string
    {
        return static::getResourceNameStatic();
    }

    public static function getResourceNameStatic(): string
    {
        return UserSSHKey::RESOURCE_NAME;
    }
}
