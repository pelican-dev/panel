<?php

namespace App\Providers;

use App\Extensions\Hashids;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Extensions\HashidsInterface;

class HashidsServiceProvider extends ServiceProvider
{
    /**
     * Register the ability to use Hashids.
     */
    public function register(): void
    {
        $this->app->singleton(HashidsInterface::class, function () {
            /** @var \Illuminate\Contracts\Config\Repository $config */
            $config = $this->app['config'];

            return new Hashids(
                $config->get('hashids.salt', ''),
                $config->get('hashids.length', 0),
                $config->get('hashids.alphabet', 'abcdefghijkmlnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
            );
        });

        $this->app->alias(HashidsInterface::class, 'hashids');
    }
}
