<?php

namespace App\Providers\Extensions;

use App\Extensions\BackupAdapter\BackupAdapterService;
use App\Extensions\BackupAdapter\Schemas\S3BackupSchema;
use App\Extensions\BackupAdapter\Schemas\WingsBackupSchema;
use App\Repositories\Daemon\DaemonBackupRepository;
use App\Services\Nodes\NodeJWTService;
use Illuminate\Support\ServiceProvider;

class BackupAdapterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BackupAdapterService::class, function ($app) {
            $service = new BackupAdapterService();

            // Default Backup adapter providers
            $service->register(new WingsBackupSchema($app->make(DaemonBackupRepository::class), $app->make(NodeJWTService::class)));
            $service->register(new S3BackupSchema($app->make(DaemonBackupRepository::class)));

            return $service;
        });
    }
}
