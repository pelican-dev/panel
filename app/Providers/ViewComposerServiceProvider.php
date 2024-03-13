<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\AssetComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot(): void
    {
        $this->app->make('view')->composer('*', AssetComposer::class);
    }
}
