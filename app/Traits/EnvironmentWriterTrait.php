<?php

namespace App\Traits;

use Illuminate\Support\Env;
use RuntimeException;

trait EnvironmentWriterTrait
{
    /**
     * Update the .env file for the application using the passed in values.
     *
     * @param  array<string, mixed>  $values
     *
     * @throws RuntimeException
     */
    public function writeToEnvironment(array $values = []): void
    {
        Env::writeVariables($values, base_path('.env'), true);
    }
}
