<?php

namespace App\Checks;

use App\Enums\DatabaseDriver;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class DatabaseExtensionCheck extends Check
{
    protected DatabaseDriver $driver = DatabaseDriver::SQLite;

    public function driver(DatabaseDriver|string $driver): self
    {
        $this->driver = is_string($driver) ? DatabaseDriver::from($driver) : $driver;

        return $this;
    }

    public function run(): Result
    {
        $extension = $this->driver->requiredExtension();
        $passed = extension_loaded($extension);

        $result = Result::make()->meta([
            'driver' => $this->driver->value,
            'extension' => $extension,
            'remediation' => trans('installer.health.extensions.remediation'),
        ]);

        return $passed
            ? $result->ok(trans('installer.health.database_extension.passed', ['extension' => $extension]))
            : $result->failed(trans('installer.health.database_extension.failed', [
                'driver' => $this->driver->getLabel(),
                'extension' => $extension,
            ]));
    }
}
