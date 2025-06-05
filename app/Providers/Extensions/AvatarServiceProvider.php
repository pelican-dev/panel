<?php

namespace App\Providers\Extensions;

use App\Extensions\Avatar\AvatarService;
use App\Extensions\Avatar\Schemas\GravatarSchema;
use App\Extensions\Avatar\Schemas\UiAvatarsSchema;
use Illuminate\Support\ServiceProvider;

class AvatarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AvatarService::class, function ($app) {
            $service = new AvatarService();

            // Default Avatar providers
            $service->register(new GravatarSchema());
            $service->register(new UiAvatarsSchema());

            return $service;
        });
    }
}
