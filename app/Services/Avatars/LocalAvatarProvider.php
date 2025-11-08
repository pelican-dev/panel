<?php

namespace App\Services\Avatars;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
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
            backgroundColor: Color::Gray[950],
            textColor: 'FFFFFF',
            size: 128
        );
    }
}
