<?php

namespace App\Extensions\Avatar\Schemas;

use App\Extensions\Avatar\AvatarSchemaInterface;
use App\Models\User;

class UiAvatarsSchema implements AvatarSchemaInterface
{
    public function getId(): string
    {
        return 'uiavatars';
    }

    public function getName(): string
    {
        return 'UI Avatars';
    }

    public function get(User $user): ?string
    {
        // UI Avatars is the default of filament so just return null here
        return null;
    }
}
