<?php

return [
    App\Providers\ActivityLogServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\BackupsServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\Extensions\AvatarServiceProvider::class,
    App\Providers\Extensions\CaptchaServiceProvider::class,
    App\Providers\Extensions\FeatureServiceProvider::class,
    App\Providers\Extensions\OAuthServiceProvider::class,
    App\Providers\Extensions\TaskServiceProvider::class,
    App\Providers\Filament\FilamentServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\AppPanelProvider::class,
    App\Providers\Filament\ServerPanelProvider::class,
    App\Providers\RouteServiceProvider::class,
    SocialiteProviders\Manager\ServiceProvider::class,
];
