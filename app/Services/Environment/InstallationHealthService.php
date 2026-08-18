<?php

namespace App\Services\Environment;

use App\Enums\EnvironmentCheckStatus;
use App\Models\Role;
use App\Models\User;
use App\ValueObjects\EnvironmentCheckResult;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class InstallationHealthService
{
    public const MIN_PHP_VERSION = '8.3.0';

    public const REQUIRED_EXTENSIONS = [
        'bcmath',
        'curl',
        'gd',
        'intl',
        'json',
        'mbstring',
        'pdo',
        'xml',
        'zip',
    ];

    public const DATABASE_EXTENSIONS = [
        'sqlite' => 'pdo_sqlite',
        'mariadb' => 'pdo_mysql',
        'mysql' => 'pdo_mysql',
        'pgsql' => 'pdo_pgsql',
    ];

    public function __construct(
        private readonly Migrator $migrator,
        private readonly QueueWorkerProbeService $queueWorkerProbe,
    ) {}

    /** @return EnvironmentCheckResult[] */
    public function systemRequirements(): array
    {
        return [
            $this->phpVersion(),
            $this->phpExtensions(),
            $this->writablePaths(),
        ];
    }

    public function phpVersion(): EnvironmentCheckResult
    {
        $passed = version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '>=');

        return new EnvironmentCheckResult(
            'php',
            trans('installer.health.php.label'),
            $passed ? EnvironmentCheckStatus::Passed : EnvironmentCheckStatus::Failed,
            trans($passed ? 'installer.health.php.passed' : 'installer.health.php.failed', [
                'current' => PHP_VERSION,
                'minimum' => self::MIN_PHP_VERSION,
            ]),
            $passed ? null : trans('installer.health.php.remediation', ['minimum' => self::MIN_PHP_VERSION]),
        );
    }

    public function phpExtensions(): EnvironmentCheckResult
    {
        $missing = array_values(array_filter(
            self::REQUIRED_EXTENSIONS,
            fn (string $extension) => !extension_loaded($extension),
        ));

        $availableDatabaseDrivers = array_values(array_filter(
            self::DATABASE_EXTENSIONS,
            fn (string $extension) => extension_loaded($extension),
        ));

        if ($availableDatabaseDrivers === []) {
            $missing[] = 'pdo_sqlite, pdo_mysql, or pdo_pgsql';
        }

        return new EnvironmentCheckResult(
            'extensions',
            trans('installer.health.extensions.label'),
            $missing === [] ? EnvironmentCheckStatus::Passed : EnvironmentCheckStatus::Failed,
            $missing === []
                ? trans('installer.health.extensions.passed')
                : trans('installer.health.extensions.failed', ['extensions' => implode(', ', $missing)]),
            $missing === [] ? null : trans('installer.health.extensions.remediation'),
        );
    }

    public function databaseDriverExtension(string $driver): EnvironmentCheckResult
    {
        $extension = self::DATABASE_EXTENSIONS[$driver] ?? null;
        $passed = $extension !== null && extension_loaded($extension);

        return new EnvironmentCheckResult(
            'database_extension',
            trans('installer.health.database_extension.label'),
            $passed ? EnvironmentCheckStatus::Passed : EnvironmentCheckStatus::Failed,
            $passed
                ? trans('installer.health.database_extension.passed', ['extension' => $extension])
                : trans('installer.health.database_extension.failed', ['driver' => $driver, 'extension' => $extension ?? 'unknown']),
            $passed ? null : trans('installer.health.extensions.remediation'),
        );
    }

    public function writablePaths(): EnvironmentCheckResult
    {
        $paths = [
            storage_path(),
            base_path('bootstrap/cache'),
            file_exists(base_path('.env')) ? base_path('.env') : base_path(),
        ];

        $notWritable = array_values(array_filter(
            $paths,
            fn (string $path) => !is_writable($path),
        ));

        return new EnvironmentCheckResult(
            'paths',
            trans('installer.health.paths.label'),
            $notWritable === [] ? EnvironmentCheckStatus::Passed : EnvironmentCheckStatus::Failed,
            $notWritable === []
                ? trans('installer.health.paths.passed')
                : trans('installer.health.paths.failed', ['paths' => implode(', ', $notWritable)]),
            $notWritable === [] ? null : trans('installer.health.paths.remediation'),
        );
    }

    public function applicationKey(): EnvironmentCheckResult
    {
        $key = config('app.key');
        $cipher = config('app.cipher', 'AES-256-CBC');

        if (is_string($key) && str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7), true);
        }

        $passed = is_string($key)
            && is_string($cipher)
            && Encrypter::supported($key, $cipher);

        return $this->simpleResult(
            'app_key',
            trans('installer.health.app_key.label'),
            $passed,
            trans($passed ? 'installer.health.app_key.passed' : 'installer.health.app_key.failed'),
            trans('installer.health.app_key.remediation'),
        );
    }

    public function installedFlag(): EnvironmentCheckResult
    {
        $passed = (bool) config('app.installed');

        return $this->simpleResult(
            'installed',
            trans('installer.health.installed.label'),
            $passed,
            trans($passed ? 'installer.health.installed.passed' : 'installer.health.installed.failed'),
            trans('installer.health.installed.remediation'),
        );
    }

    public function database(): EnvironmentCheckResult
    {
        $connection = (string) config('database.default');

        try {
            DB::connection($connection)->getPdo();

            return new EnvironmentCheckResult(
                'database',
                trans('installer.health.database.label'),
                EnvironmentCheckStatus::Passed,
                trans('installer.health.database.passed', ['connection' => $connection]),
            );
        } catch (Throwable $exception) {
            return new EnvironmentCheckResult(
                'database',
                trans('installer.health.database.label'),
                EnvironmentCheckStatus::Failed,
                trans('installer.health.database.failed', ['error' => $exception->getMessage()]),
                trans('installer.health.database.remediation'),
            );
        }
    }

    public function migrations(): EnvironmentCheckResult
    {
        try {
            if (!$this->migrator->repositoryExists()) {
                return $this->simpleResult(
                    'migrations',
                    trans('installer.health.migrations.label'),
                    false,
                    trans('installer.health.migrations.repository_missing'),
                    trans('installer.health.migrations.remediation'),
                );
            }

            $files = $this->migrator->getMigrationFiles(database_path('migrations'));
            $pending = array_diff(array_keys($files), $this->migrator->getRepository()->getRan());

            return $this->simpleResult(
                'migrations',
                trans('installer.health.migrations.label'),
                $pending === [],
                $pending === []
                    ? trans('installer.health.migrations.passed')
                    : trans('installer.health.migrations.failed', ['count' => count($pending)]),
                trans('installer.health.migrations.remediation'),
            );
        } catch (Throwable $exception) {
            return $this->simpleResult(
                'migrations',
                trans('installer.health.migrations.label'),
                false,
                trans('installer.health.migrations.exception', ['error' => $exception->getMessage()]),
                trans('installer.health.migrations.remediation'),
            );
        }
    }

    public function adminUser(): EnvironmentCheckResult
    {
        try {
            $passed = User::role(Role::ROOT_ADMIN)->exists();

            return $this->simpleResult(
                'admin',
                trans('installer.health.admin.label'),
                $passed,
                trans($passed ? 'installer.health.admin.passed' : 'installer.health.admin.failed'),
                trans('installer.health.admin.remediation'),
            );
        } catch (Throwable $exception) {
            return $this->simpleResult(
                'admin',
                trans('installer.health.admin.label'),
                false,
                trans('installer.health.admin.exception', ['error' => $exception->getMessage()]),
                trans('installer.health.admin.remediation'),
            );
        }
    }

    public function cache(): EnvironmentCheckResult
    {
        $key = 'pelican:environment-check:' . Str::random(16);
        $value = Str::random(24);

        try {
            Cache::put($key, $value, 30);
            $passed = Cache::get($key) === $value;
            Cache::forget($key);

            return $this->simpleResult(
                'cache',
                trans('installer.health.cache.label'),
                $passed,
                trans($passed ? 'installer.health.cache.passed' : 'installer.health.cache.failed'),
                trans('installer.health.cache.remediation'),
            );
        } catch (Throwable $exception) {
            return $this->simpleResult(
                'cache',
                trans('installer.health.cache.label'),
                false,
                trans('installer.health.cache.exception', ['error' => $exception->getMessage()]),
                trans('installer.health.cache.remediation'),
            );
        }
    }

    public function queueWorker(int $timeoutSeconds = 10): EnvironmentCheckResult
    {
        return $this->queueWorkerProbe->check(timeoutSeconds: $timeoutSeconds);
    }

    /** @return EnvironmentCheckResult[] */
    public function configuredEnvironment(bool $includeQueue = true, int $queueTimeoutSeconds = 10): array
    {
        $results = [
            ...$this->systemRequirements(),
            $this->applicationKey(),
            $this->database(),
            $this->migrations(),
            $this->cache(),
        ];

        if ($includeQueue) {
            $results[] = $this->queueWorker($queueTimeoutSeconds);
        }

        return $results;
    }

    /** @return EnvironmentCheckResult[] */
    public function completeInstallation(bool $includeQueue = true, int $queueTimeoutSeconds = 10): array
    {
        return [
            ...$this->configuredEnvironment($includeQueue, $queueTimeoutSeconds),
            $this->adminUser(),
            $this->installedFlag(),
        ];
    }

    /** @param EnvironmentCheckResult[] $results */
    public function hasFailures(array $results): bool
    {
        foreach ($results as $result) {
            if ($result->failed()) {
                return true;
            }
        }

        return false;
    }

    private function simpleResult(
        string $key,
        string $label,
        bool $passed,
        string $message,
        ?string $remediation = null,
    ): EnvironmentCheckResult {
        return new EnvironmentCheckResult(
            $key,
            $label,
            $passed ? EnvironmentCheckStatus::Passed : EnvironmentCheckStatus::Failed,
            $message,
            $passed ? null : $remediation,
        );
    }
}
