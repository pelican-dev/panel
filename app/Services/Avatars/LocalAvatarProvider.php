<?php

namespace App\Services\Avatars;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class LocalAvatarProvider implements AvatarProvider
{
    public function __construct(protected LocalAvatarService $avatarService) {}

    public function get(Model|Authenticatable $record): string
    {
        $name = Filament::getNameForDefaultAvatar($record);

        return $this->avatarService->generateDataUri(
            name: $name,
            backgroundColor: $this->avatarService->generateColorFromName($name),
            textColor: 'FFFFFF',
            size: 128
        );
    }
}
