<?php

namespace App\Extensions\Avatar;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AvatarService
{
    /** @var AvatarSchemaInterface[] */
    private array $schemas = [];

    /**
     * @return AvatarSchemaInterface[] | AvatarSchemaInterface | null
     */
    public function get(?string $id = null): array|AvatarSchemaInterface|null
    {
        return $id ? array_get($this->schemas, $id) : $this->schemas;
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

    public function register(AvatarSchemaInterface $schema): void
    {
        if (array_key_exists($schema->getId(), $this->schemas)) {
            return;
        }

        $this->schemas[$schema->getId()] = $schema;
    }
}
