<?php

namespace App\Services\Api;

use App\Models\ApiKey;

class KeyCreationService
{
    private int $keyType = ApiKey::TYPE_NONE;

    /**
     * Set the type of key that should be created. By default, an orphaned key will be
     * created. These keys cannot be used for anything, and will not render in the UI.
     */
    public function setKeyType(int $type): self
    {
        $this->keyType = $type;

        return $this;
    }

    /**
     * Create a new API key for the Panel using the permissions passed in the data request.
     * This will automatically generate an identifier and an encrypted token that are
     * stored in the database.
     *
     * @param  array<mixed>  $data
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function handle(array $data): ApiKey
    {
        $data = array_merge($data, [
            'key_type' => $this->keyType,
            'identifier' => ApiKey::generateTokenIdentifier($this->keyType),
            'token' => str_random(ApiKey::KEY_LENGTH),
        ]);

        if ($this->keyType !== ApiKey::TYPE_APPLICATION) {
            unset($data['permissions']);
        }

        return ApiKey::query()->forceCreate($data);
    }
}
