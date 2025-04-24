<?php

namespace App\Extensions\Avatar\Providers;

use App\Extensions\Avatar\AvatarProvider;
use App\Models\User;

class UiAvatarsProvider extends AvatarProvider
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

    public static function register(): self
    {
        return new self();
    }
}
