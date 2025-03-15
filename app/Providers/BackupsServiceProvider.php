<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Extensions\Backups\BackupManager;
use Illuminate\Contracts\Support\DeferrableProvider;

class BackupsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the S3 backup disk.
     */
    public function register(): void
    {
        $this->app->singleton(BackupManager::class, function ($app) {
            return new BackupManager($app);
        });
    }

    /**
     * @return class-string[]
     */
    public function provides(): array
    {
        return [BackupManager::class];
    }
}
