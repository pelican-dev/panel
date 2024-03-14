<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\EggRepository;
use App\Repositories\Eloquent\NodeRepository;
use App\Repositories\Eloquent\TaskRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\ApiKeyRepository;
use App\Repositories\Eloquent\ServerRepository;
use App\Repositories\Eloquent\SessionRepository;
use App\Repositories\Eloquent\SubuserRepository;
use App\Repositories\Eloquent\DatabaseRepository;
use App\Repositories\Eloquent\LocationRepository;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\SettingsRepository;
use App\Repositories\Eloquent\AllocationRepository;
use App\Contracts\Repository\EggRepositoryInterface;
use App\Repositories\Eloquent\EggVariableRepository;
use App\Contracts\Repository\NodeRepositoryInterface;
use App\Contracts\Repository\TaskRepositoryInterface;
use App\Contracts\Repository\UserRepositoryInterface;
use App\Repositories\Eloquent\DatabaseHostRepository;
use App\Contracts\Repository\ApiKeyRepositoryInterface;
use App\Contracts\Repository\ServerRepositoryInterface;
use App\Repositories\Eloquent\ServerVariableRepository;
use App\Contracts\Repository\SessionRepositoryInterface;
use App\Contracts\Repository\SubuserRepositoryInterface;
use App\Contracts\Repository\DatabaseRepositoryInterface;
use App\Contracts\Repository\LocationRepositoryInterface;
use App\Contracts\Repository\ScheduleRepositoryInterface;
use App\Contracts\Repository\SettingsRepositoryInterface;
use App\Contracts\Repository\AllocationRepositoryInterface;
use App\Contracts\Repository\EggVariableRepositoryInterface;
use App\Contracts\Repository\DatabaseHostRepositoryInterface;
use App\Contracts\Repository\ServerVariableRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register all the repository bindings.
     */
    public function register(): void
    {
        // Eloquent Repositories
        $this->app->bind(AllocationRepositoryInterface::class, AllocationRepository::class);
        $this->app->bind(ApiKeyRepositoryInterface::class, ApiKeyRepository::class);
        $this->app->bind(DatabaseRepositoryInterface::class, DatabaseRepository::class);
        $this->app->bind(DatabaseHostRepositoryInterface::class, DatabaseHostRepository::class);
        $this->app->bind(EggRepositoryInterface::class, EggRepository::class);
        $this->app->bind(EggVariableRepositoryInterface::class, EggVariableRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(NodeRepositoryInterface::class, NodeRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(ServerRepositoryInterface::class, ServerRepository::class);
        $this->app->bind(ServerVariableRepositoryInterface::class, ServerVariableRepository::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);
        $this->app->bind(SettingsRepositoryInterface::class, SettingsRepository::class);
        $this->app->bind(SubuserRepositoryInterface::class, SubuserRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
