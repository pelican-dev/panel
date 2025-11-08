<?php

namespace App\AvatarProviders;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class PelicanAvatarProvider implements AvatarProvider
{
    public function get(Model|Authenticatable $record): string
    {
        $logo = config('app.logo');

        if (filled($logo)) {
            if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
                return $logo;
            }

            return url($logo);
        }

        return url('/pelican.svg');
    }
}
