<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\NodeRepository;
use App\Repositories\Eloquent\SubuserRepository;
use App\Repositories\Eloquent\EggVariableRepository;
use App\Contracts\Repository\NodeRepositoryInterface;
use App\Repositories\Eloquent\DatabaseHostRepository;
use App\Contracts\Repository\SubuserRepositoryInterface;
use App\Contracts\Repository\EggVariableRepositoryInterface;
use App\Contracts\Repository\DatabaseHostRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register all the repository bindings.
     */
    public function register(): void
    {
        // Eloquent Repositories
        $this->app->bind(DatabaseHostRepositoryInterface::class, DatabaseHostRepository::class);
        $this->app->bind(EggVariableRepositoryInterface::class, EggVariableRepository::class);
        $this->app->bind(NodeRepositoryInterface::class, NodeRepository::class);
        $this->app->bind(SubuserRepositoryInterface::class, SubuserRepository::class);
    }
}
