<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\DatabaseHostRepository;
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
    }
}
