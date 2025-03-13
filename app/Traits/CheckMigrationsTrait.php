<?php

namespace App\Traits;

use Illuminate\Database\Migrations\Migrator;

trait CheckMigrationsTrait
{
    /**
     * Checks if the migrations have finished running by comparing the last migration file.
     */
    protected function hasCompletedMigrations(): bool
    {
        /** @var Migrator $migrator */
        $migrator = app()->make('migrator'); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions

        $files = $migrator->getMigrationFiles(database_path('migrations'));

        if (!$migrator->repositoryExists()) {
            return false;
        }

        if (array_diff(array_keys($files), $migrator->getRepository()->getRan())) {
            return false;
        }

        return true;
    }
}
