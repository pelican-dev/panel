<?php

namespace App\Providers\Extensions;

use App\Extensions\Avatar\AvatarProvider;
use App\Extensions\Avatar\Schemas\GravatarSchema;
use App\Extensions\Avatar\Schemas\UiAvatarsSchema;
use Illuminate\Support\ServiceProvider;

class AvatarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AvatarProvider::class, function ($app) {
            $provider = new AvatarProvider();

            // Default Avatar providers
            $provider->register(new GravatarSchema());
            $provider->register(new UiAvatarsSchema());

            return $provider;
        });
    }
}
