<?php

namespace App\Extensions\Avatar\Providers;

use App\Extensions\Avatar\AvatarProvider;
use Filament\AvatarProviders\UiAvatarsProvider as FilamentUiAvatarsProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LocalAvatarProvider extends AvatarProvider
{
    public function getId(): string
    {
        return 'local';
    }

    public function get(Model|Authenticatable $record): string
    {
        $path = 'avatars/' . $record->getKey() . '.png';

        return Storage::disk('public')->exists($path) ? Storage::url($path) : (new FilamentUiAvatarsProvider())->get($record);
    }

    public static function register(): self
    {
        return new self();
    }
}
