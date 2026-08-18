<?php

namespace App\Services\Maintenance;

use App\Services\Helpers\SoftwareVersionService;
use App\ValueObjects\UpdateSnapshot;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class UpdateSnapshotService
{
    public function __construct(private readonly SoftwareVersionService $versionService) {}

    public function capture(
        ?string $targetVersion = null,
        ?string $snapshotRoot = null,
        ?string $environmentPath = null,
    ): UpdateSnapshot {
        $snapshotRoot ??= storage_path('app/private/update-snapshots');
        $environmentPath ??= base_path('.env');
        if (!File::isFile($environmentPath)) {
            throw new RuntimeException(trans('commands.update.environment_missing'));
        }

        $snapshotPath = $snapshotRoot . DIRECTORY_SEPARATOR . now()->format('Ymd-His') . '-' . Str::lower(Str::random(6));

        File::ensureDirectoryExists($snapshotPath, 0700, true);

        $environmentSnapshot = $snapshotPath . DIRECTORY_SEPARATOR . '.env';
        File::copy($environmentPath, $environmentSnapshot);
        @chmod($environmentSnapshot, 0600);

        foreach (['composer.json', 'composer.lock'] as $file) {
            if (File::isFile(base_path($file))) {
                File::copy(base_path($file), $snapshotPath . DIRECTORY_SEPARATOR . $file);
            }
        }

        $databaseGuidance = $this->captureDatabaseState($snapshotPath);
        $rollbackGuide = $snapshotPath . DIRECTORY_SEPARATOR . 'ROLLBACK.md';

        File::put($rollbackGuide, $this->rollbackGuide($snapshotPath, $databaseGuidance));
        File::put($snapshotPath . DIRECTORY_SEPARATOR . 'metadata.json', json_encode([
            'created_at' => now()->toIso8601String(),
            'current_version' => $this->versionService->currentPanelVersion(),
            'target_version' => $targetVersion,
            'php_version' => PHP_VERSION,
            'database_driver' => config('database.default'),
        ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));

        return new UpdateSnapshot($snapshotPath, $rollbackGuide, $databaseGuidance);
    }

    public function latest(?string $snapshotRoot = null): ?UpdateSnapshot
    {
        $snapshotRoot ??= storage_path('app/private/update-snapshots');
        if (!File::isDirectory($snapshotRoot)) {
            return null;
        }

        $directories = collect(File::directories($snapshotRoot))
            ->sortByDesc(fn (string $directory) => File::lastModified($directory));

        $path = $directories->first();
        if (!is_string($path)) {
            return null;
        }

        $rollbackGuide = $path . DIRECTORY_SEPARATOR . 'ROLLBACK.md';
        if (!File::isFile($rollbackGuide)) {
            return null;
        }

        $databaseGuidancePath = $path . DIRECTORY_SEPARATOR . 'DATABASE-BACKUP.txt';
        $databaseGuidance = File::isFile($databaseGuidancePath)
            ? trim(File::get($databaseGuidancePath))
            : trans('commands.update.database_backup_unknown');

        return new UpdateSnapshot($path, $rollbackGuide, $databaseGuidance);
    }

    public function fromPath(string $path): ?UpdateSnapshot
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $rollbackGuide = $path . DIRECTORY_SEPARATOR . 'ROLLBACK.md';
        if (!File::isDirectory($path) || !File::isFile($rollbackGuide)) {
            return null;
        }

        $databaseGuidancePath = $path . DIRECTORY_SEPARATOR . 'DATABASE-BACKUP.txt';

        return new UpdateSnapshot(
            $path,
            $rollbackGuide,
            File::isFile($databaseGuidancePath)
                ? trim(File::get($databaseGuidancePath))
                : trans('commands.update.database_backup_unknown'),
        );
    }

    private function captureDatabaseState(string $snapshotPath): string
    {
        $driver = (string) config('database.default');

        if ($driver === 'sqlite') {
            $database = config('database.connections.sqlite.database');
            if (is_string($database) && File::isFile($database)) {
                $destination = $snapshotPath . DIRECTORY_SEPARATOR . 'database.sqlite';
                File::copy($database, $destination);
                @chmod($destination, 0600);

                $guidance = trans('commands.update.database_backup_sqlite', ['path' => $destination]);
                File::put($snapshotPath . DIRECTORY_SEPARATOR . 'DATABASE-BACKUP.txt', $guidance);

                return $guidance;
            }
        }

        $guidance = match ($driver) {
            'mariadb', 'mysql' => trans('commands.update.database_backup_mysql'),
            'pgsql' => trans('commands.update.database_backup_pgsql'),
            default => trans('commands.update.database_backup_unknown'),
        };

        File::put($snapshotPath . DIRECTORY_SEPARATOR . 'DATABASE-BACKUP.txt', $guidance);

        return $guidance;
    }

    private function rollbackGuide(string $snapshotPath, string $databaseGuidance): string
    {
        return <<<MARKDOWN
# Pelican Panel update rollback

This snapshot was created before application files were changed. It contains secrets and must remain readable only by the Panel administrator.

1. Keep the Panel in maintenance mode: `php artisan down`.
2. Preserve logs and the failed release before replacing any files.
3. Restore the previous release files or Git revision.
4. Restore `.env` from `{$snapshotPath}` and keep its original ownership and restrictive permissions.
5. Restore the pre-update database only when the failed update ran migrations. Do not attempt to reverse migrations by hand.
6. Run `composer install --no-dev --optimize-autoloader` from the restored release.
7. Run `php artisan optimize:clear`.
8. Run `php artisan p:environment:health` and only run `php artisan up` after every required check passes.

Database backup guidance recorded before the update:

{$databaseGuidance}
MARKDOWN;
    }
}
