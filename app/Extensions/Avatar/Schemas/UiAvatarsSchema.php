<?php

namespace App\Extensions\Avatar\Schemas;

use App\Extensions\Avatar\AvatarSchemaInterface;
use Filament\AvatarProviders\UiAvatarsProvider;

class UiAvatarsSchema extends UiAvatarsProvider implements AvatarSchemaInterface
{
    public function getId(): string
    {
        return 'uiavatars';
    }

    public function getName(): string
    {
        return 'UI Avatars';
    }
}
