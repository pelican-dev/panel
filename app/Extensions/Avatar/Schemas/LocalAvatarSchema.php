<?php

namespace App\Extensions\Avatar\Schemas;

use App\Extensions\Avatar\AvatarSchemaInterface;
use App\Models\User;
use App\Services\Avatars\LocalAvatarService;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;

class LocalAvatarSchema implements AvatarSchemaInterface
{
    public function __construct(protected LocalAvatarService $avatarService) {}

    public function getId(): string
    {
        return 'local';
    }

    public function getName(): string
    {
        return 'Local Avatar';
    }

    public function get(User $user): ?string
    {
        $name = Filament::getNameForDefaultAvatar($user);

        return $this->avatarService->generateDataUri(
            name: $name,
            backgroundColor: $this->avatarService->generateColorFromName($name),
            textColor: 'FFFFFF',
            size: 128
        );
    }
}
