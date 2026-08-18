<?php

namespace App\Services\Maintenance;

use App\Services\Helpers\SoftwareVersionService;
use App\ValueObjects\UpdateSnapshot;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use PDO;
use RuntimeException;
use Throwable;

class UpdateSnapshotService
{
    private const REQUIRED_ARTIFACTS = [
        '.env',
        'composer.json',
        'composer.lock',
        'DATABASE-BACKUP.txt',
        'ROLLBACK.md',
        'metadata.json',
    ];

    public function __construct(
        private readonly SoftwareVersionService $versionService,
        private readonly Filesystem $files,
    ) {}

    public function capture(
        ?string $targetVersion = null,
        ?string $snapshotRoot = null,
        ?string $environmentPath = null,
    ): UpdateSnapshot {
        $snapshotRoot ??= storage_path('app/private/update-snapshots');
        $environmentPath ??= base_path('.env');
        if (!$this->files->isFile($environmentPath)) {
            throw new RuntimeException(trans('commands.update.environment_missing'));
        }

        $snapshotPath = $snapshotRoot . DIRECTORY_SEPARATOR . now()->format('Ymd-His') . '-' . Str::lower(Str::random(6));

        $this->files->ensureDirectoryExists($snapshotPath, 0700, true);

        try {
            $environmentSnapshot = $snapshotPath . DIRECTORY_SEPARATOR . '.env';
            $this->copyRequiredArtifact($environmentPath, $environmentSnapshot);
            @chmod($environmentSnapshot, 0600);

            foreach (['composer.json', 'composer.lock'] as $file) {
                $this->copyRequiredArtifact(base_path($file), $snapshotPath . DIRECTORY_SEPARATOR . $file);
            }

            $databaseGuidance = $this->captureDatabaseState($snapshotPath);
            $rollbackGuide = $snapshotPath . DIRECTORY_SEPARATOR . 'ROLLBACK.md';

            $this->writeRequiredArtifact($rollbackGuide, $this->rollbackGuide($snapshotPath, $databaseGuidance));
            $this->writeRequiredArtifact($snapshotPath . DIRECTORY_SEPARATOR . 'metadata.json', json_encode([
                'created_at' => now()->toIso8601String(),
                'current_version' => $this->versionService->currentPanelVersion(),
                'target_version' => $targetVersion,
                'php_version' => PHP_VERSION,
                'database_driver' => config('database.default'),
            ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));

            $snapshot = $this->validatedSnapshot($snapshotPath);
            if ($snapshot === null) {
                throw new RuntimeException(trans('commands.update.snapshot_incomplete', ['path' => $snapshotPath]));
            }

            return $snapshot;
        } catch (Throwable $exception) {
            $this->files->deleteDirectory($snapshotPath);

            throw $exception;
        }
    }

    public function latest(?string $snapshotRoot = null): ?UpdateSnapshot
    {
        $snapshotRoot ??= storage_path('app/private/update-snapshots');
        if (!$this->files->isDirectory($snapshotRoot)) {
            return null;
        }

        $directories = collect($this->files->directories($snapshotRoot))
            ->sortByDesc(fn (string $directory) => $this->files->lastModified($directory));

        foreach ($directories as $directory) {
            if (($snapshot = $this->validatedSnapshot($directory)) !== null) {
                return $snapshot;
            }
        }

        return null;
    }

    public function fromPath(string $path): ?UpdateSnapshot
    {
        return $this->validatedSnapshot($path);
    }

    private function captureDatabaseState(string $snapshotPath): string
    {
        $driver = (string) config('database.default');

        if ($driver === 'sqlite') {
            $database = config('database.connections.sqlite.database');
            if (!is_string($database) || !$this->files->isFile($database)) {
                throw new RuntimeException(trans('commands.update.sqlite_database_missing'));
            }

            $destination = $snapshotPath . DIRECTORY_SEPARATOR . 'database.sqlite';
            $this->backupSqliteDatabase($database, $destination);

            $guidance = trans('commands.update.database_backup_sqlite', ['path' => $destination]);
            $this->writeRequiredArtifact($snapshotPath . DIRECTORY_SEPARATOR . 'DATABASE-BACKUP.txt', $guidance);

            return $guidance;
        }

        $guidance = match ($driver) {
            'mariadb', 'mysql' => trans('commands.update.database_backup_mysql'),
            'pgsql' => trans('commands.update.database_backup_pgsql'),
            default => trans('commands.update.database_backup_unknown'),
        };

        $this->writeRequiredArtifact($snapshotPath . DIRECTORY_SEPARATOR . 'DATABASE-BACKUP.txt', $guidance);

        return $guidance;
    }

    private function backupSqliteDatabase(string $database, string $destination): void
    {
        $connection = new PDO('sqlite:' . $database, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $connection->exec('PRAGMA busy_timeout = 5000');

        $quotedDestination = $connection->quote($destination);
        if (!is_string($quotedDestination)) {
            throw new RuntimeException(trans('commands.update.snapshot_write_failed', ['artifact' => $destination]));
        }

        $connection->exec("VACUUM INTO {$quotedDestination}");

        if (!$this->files->isFile($destination)) {
            throw new RuntimeException(trans('commands.update.snapshot_write_failed', ['artifact' => $destination]));
        }

        @chmod($destination, 0600);
    }

    private function copyRequiredArtifact(string $source, string $destination): void
    {
        if (!$this->files->isFile($source) || !$this->files->copy($source, $destination)) {
            throw new RuntimeException(trans('commands.update.snapshot_write_failed', ['artifact' => $destination]));
        }
    }

    private function writeRequiredArtifact(string $path, string $contents): void
    {
        if ($this->files->put($path, $contents) === false) {
            throw new RuntimeException(trans('commands.update.snapshot_write_failed', ['artifact' => $path]));
        }
    }

    private function validatedSnapshot(string $path): ?UpdateSnapshot
    {
        $path = rtrim($path, '/\\');
        if (!$this->files->isDirectory($path)) {
            return null;
        }

        foreach (self::REQUIRED_ARTIFACTS as $artifact) {
            if (!$this->files->isFile($path . DIRECTORY_SEPARATOR . $artifact)) {
                return null;
            }
        }

        try {
            $metadata = $this->files->json($path . DIRECTORY_SEPARATOR . 'metadata.json');
            if (!is_array($metadata) || !is_string($metadata['database_driver'] ?? null)) {
                return null;
            }

            if ($metadata['database_driver'] === 'sqlite'
                && !$this->files->isFile($path . DIRECTORY_SEPARATOR . 'database.sqlite')) {
                return null;
            }

            $databaseGuidance = trim($this->files->get($path . DIRECTORY_SEPARATOR . 'DATABASE-BACKUP.txt'));
            if ($databaseGuidance === '') {
                return null;
            }
        } catch (Throwable) {
            return null;
        }

        return new UpdateSnapshot(
            $path,
            $path . DIRECTORY_SEPARATOR . 'ROLLBACK.md',
            $databaseGuidance,
        );
    }

    private function rollbackGuide(string $snapshotPath, string $databaseGuidance): string
    {
        return <<<MARKDOWN
# Pelican Panel update rollback

This snapshot was created before application files were changed. It contains secrets and must remain readable only by the Panel administrator.

1. Keep the Panel in maintenance mode: `php artisan down`.
2. Preserve logs and the failed release before replacing any files.
3. Restore the previous release files or Git revision.
4. Restore `.env` from `{$snapshotPath}/.env` and keep its original ownership and restrictive permissions.
5. Restore the pre-update database only when the failed update ran migrations. Do not attempt to reverse migrations by hand.
6. Run `composer install --no-dev --optimize-autoloader` from the restored release.
7. Run `php artisan optimize:clear`.
8. Run `php artisan p:environment:health` and only run `php artisan up` after every required check passes.

Database backup guidance recorded before the update:

{$databaseGuidance}
MARKDOWN;
    }
}
