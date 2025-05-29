<?php

namespace App\Extensions\Avatar;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AvatarProvider
{
    /** @var AvatarSchemaInterface[] */
    private array $providers = [];

    /**
     * @return AvatarSchemaInterface[] | AvatarSchemaInterface | null
     */
    public function get(?string $id = null): array|AvatarSchemaInterface|null
    {
        return $id ? array_get($this->providers, $id) : $this->providers;
    }

    public function getActiveSchema(): ?AvatarSchemaInterface
    {
        return $this->get(config('panel.filament.avatar-provider'));
    }

    public function getAvatarUrl(User $user): ?string
    {
        if (config('panel.filament.uploadable-avatars')) {
            $path = "avatars/$user->id.png";

            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }

        return $this->getActiveSchema()?->get($user);
    }

    public function register(AvatarSchemaInterface $provider): void
    {
        if (array_key_exists($provider->getId(), $this->providers)) {
            return;
        }

        $this->providers[$provider->getId()] = $provider;
    }
}
