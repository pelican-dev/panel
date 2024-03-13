<?php

namespace App\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
