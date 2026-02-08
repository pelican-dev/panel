<?php

namespace App\Services\Pwa;

use App\Models\PwaSetting;
use Illuminate\Support\Facades\Crypt;
use Minishlink\WebPush\VAPID;
use Throwable;

class PwaSettingsRepository
{
    private const ENCRYPTED_KEY = 'vapid_private_key';

    public function get(string $key, mixed $default = null): mixed
    {
        $record = PwaSetting::query()->where('key', $key)->first();

        if (!$record) {
            return $default;
        }

        $value = $record->value;

        if ($key === self::ENCRYPTED_KEY) {
            try {
                return Crypt::decryptString($value);
            } catch (Throwable) {
                return $value;
            }
        }

        return $value;
    }

    public function set(string $key, mixed $value): void
    {
        if ($key === self::ENCRYPTED_KEY) {
            $value = Crypt::encryptString($value);
        }

        PwaSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /** @param  array<string, mixed>  $values */
    public function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    public function allWithDefaults(array $defaults): array
    {
        $settings = [];

        foreach ($defaults as $key => $default) {
            $settings[$key] = $this->get($key, $default);
        }

        return $settings;
    }

    /**
     * Generate VAPID keys
     *
     * @return array{publicKey: string, privateKey: string}|null
     */
    public function ensureVapidKeys(): ?array
    {
        $publicKey = $this->get('vapid_public_key', '');
        $privateKey = $this->get('vapid_private_key', '');

        if ($publicKey && $privateKey) {
            return ['publicKey' => $publicKey, 'privateKey' => $privateKey];
        }

        try {
            $keys = VAPID::createVapidKeys();
        } catch (Throwable) {
            return null;
        }

        $this->set('vapid_public_key', $keys['publicKey']);
        $this->set('vapid_private_key', $keys['privateKey']);

        return $keys;
    }
}
