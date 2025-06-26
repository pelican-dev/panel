<?php

namespace App\Extensions\Avatar;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AvatarService
{
    /** @var AvatarSchemaInterface[] */
    private array $schemas = [];

    public function __construct(
        private readonly bool $allowUploadedAvatars,
        private readonly string $activeSchema,
    ) {}

    public function get(string $id): ?AvatarSchemaInterface
    {
        return array_get($this->schemas, $id);
    }

    public function getActiveSchema(): ?AvatarSchemaInterface
    {
        return $this->get($this->activeSchema);
    }

    public function getAvatarUrl(User $user): ?string
    {
        if ($this->allowUploadedAvatars) {
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

    /** @return array<string, string> */
    public function getMappings(): array
    {
        return collect($this->schemas)->mapWithKeys(fn ($schema) => [$schema->getId() => $schema->getName()])->all();
    }
}
