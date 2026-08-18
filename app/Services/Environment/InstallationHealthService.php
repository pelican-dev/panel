<?php

namespace App\Services\Environment;

use App\Checks\AdminUserCheck;
use App\Checks\ApplicationKeyCheck;
use App\Checks\CacheCheck;
use App\Checks\DatabaseCheck;
use App\Checks\DatabaseExtensionCheck;
use App\Checks\InstallationFlagCheck;
use App\Checks\MigrationsCheck;
use App\Checks\PhpExtensionsCheck;
use App\Checks\PhpVersionCheck;
use App\Checks\WritablePathsCheck;
use App\Enums\DatabaseDriver;
use Illuminate\Support\Collection;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;
use Spatie\Health\Enums\Status;
use Throwable;

class InstallationHealthService
{
    /** @return Collection<int, Result> */
    public function systemRequirements(): Collection
    {
        return $this->run([
            PhpVersionCheck::new()->label(trans('installer.health.php.label')),
            PhpExtensionsCheck::new()->label(trans('installer.health.extensions.label')),
            WritablePathsCheck::new()->label(trans('installer.health.paths.label')),
        ]);
    }

    public function databaseDriverExtension(DatabaseDriver|string $driver): Result
    {
        return $this->runCheck(
            DatabaseExtensionCheck::new()
                ->driver($driver)
                ->label(trans('installer.health.database_extension.label')),
        );
    }

    /** @return Collection<int, Result> */
    public function configuredEnvironment(): Collection
    {
        return $this->systemRequirements()->concat($this->run([
            ApplicationKeyCheck::new()->label(trans('installer.health.app_key.label')),
            DatabaseCheck::new()->label(trans('installer.health.database.label')),
            MigrationsCheck::new()->label(trans('installer.health.migrations.label')),
            CacheCheck::new()->label(trans('installer.health.cache.label')),
        ]));
    }

    /** @return Collection<int, Result> */
    public function completeInstallation(): Collection
    {
        return $this->configuredEnvironment()->concat($this->run([
            AdminUserCheck::new()->label(trans('installer.health.admin.label')),
            InstallationFlagCheck::new()->label(trans('installer.health.installed.label')),
        ]));
    }

    /**
     * @param  iterable<Check>  $checks
     * @return Collection<int, Result>
     */
    public function run(iterable $checks): Collection
    {
        return collect($checks)->map(fn (Check $check) => $this->runCheck($check))->values();
    }

    public function runCheck(Check $check): Result
    {
        try {
            $result = $check->run();
        } catch (Throwable $exception) {
            report($exception);

            $result = $check->markAsCrashed()
                ->notificationMessage($exception->getMessage());
        }

        return $result
            ->check($check)
            ->endedAt(now());
    }

    /** @param iterable<Result> $results */
    public function hasFailures(iterable $results): bool
    {
        foreach ($results as $result) {
            if (in_array($result->status, [Status::failed(), Status::crashed()], true)) {
                return true;
            }
        }

        return false;
    }
}
