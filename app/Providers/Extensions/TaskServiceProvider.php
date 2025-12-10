<?php

namespace App\Providers\Extensions;

use App\Extensions\Tasks\Schemas\CreateBackupSchema;
use App\Extensions\Tasks\Schemas\DeleteFilesSchema;
use App\Extensions\Tasks\Schemas\PowerActionSchema;
use App\Extensions\Tasks\Schemas\SendCommandSchema;
use App\Extensions\Tasks\TaskService;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Services\Backups\InitiateBackupService;
use App\Services\Files\DeleteFilesService;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TaskService::class, function ($app) {
            $service = new TaskService();

            // Default Task providers
            $service->register(new PowerActionSchema($app->make(DaemonServerRepository::class)));
            $service->register(new SendCommandSchema());
            $service->register(new CreateBackupSchema($app->make(InitiateBackupService::class)));
            $service->register(new DeleteFilesSchema($app->make(DeleteFilesService::class)));

            return $service;
        });
    }
}
