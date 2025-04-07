<?php

namespace App\Extensions\Avatar\Providers;

use App\Extensions\Avatar\AvatarProvider;
use Filament\AvatarProviders\UiAvatarsProvider as FilamentUiAvatarsProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

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

    public function get(Model|Authenticatable $record): string
    {
        return (new FilamentUiAvatarsProvider())->get($record);
    }

    public static function register(): self
    {
        return new self();
    }
}
