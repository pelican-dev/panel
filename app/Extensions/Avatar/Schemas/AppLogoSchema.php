<?php

namespace App\Extensions\Avatar\Schemas;

use App\Extensions\Avatar\AvatarSchemaInterface;
use App\Models\User;

class AppLogoSchema implements AvatarSchemaInterface
{
    public function getId(): string
    {
        return 'appIcon';
    }

    public function getName(): string
    {
        return 'App Icon';
    }

    public function get(User $user): string
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
