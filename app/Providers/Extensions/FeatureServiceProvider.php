<?php

namespace App\Providers\Extensions;

use App\Extensions\Features\FeatureService;
use App\Extensions\Features\Schemas\GSLTokenSchema;
use App\Extensions\Features\Schemas\JavaVersionSchema;
use App\Extensions\Features\Schemas\MinecraftEulaSchema;
use App\Extensions\Features\Schemas\PIDLimitSchema;
use App\Extensions\Features\Schemas\SteamDiskSpaceSchema;
use Illuminate\Support\ServiceProvider;

class FeatureServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FeatureService::class, function ($app) {
            $provider = new FeatureService();

            // Default Feature providers
            $provider->register(new GSLTokenSchema());
            $provider->register(new JavaVersionSchema());
            $provider->register(new MinecraftEulaSchema());
            $provider->register(new PIDLimitSchema());
            $provider->register(new SteamDiskSpaceSchema());

            return $provider;
        });
    }
}
