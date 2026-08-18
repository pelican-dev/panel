<?php

namespace App\Tests\Integration\Services\Maintenance;

use App\Enums\EnvironmentCheckStatus;
use App\Services\Helpers\SoftwareVersionService;
use App\Services\Maintenance\UpdateCompatibilityService;
use App\Services\Maintenance\UpdateSnapshotService;
use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use PDO;
use RuntimeException;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class UpdateSafetyServiceTest extends IntegrationTestCase
{
    /** @var TemporaryDirectory[] */
    private array $temporaryDirectories = [];

    protected function tearDown(): void
    {
        foreach ($this->temporaryDirectories as $directory) {
            $directory->delete();
        }

        parent::tearDown();
    }

    public function test_compatibility_check_uses_the_target_release_lock_file(): void
    {
        $source = $this->temporaryDirectory();
        File::put($source->path('composer.json'), '{}');
        File::put($source->path('composer.lock'), '{}');
        Process::fake(['*' => Process::result(output: 'All platform requirements satisfied.')]);

        $result = $this->app->make(UpdateCompatibilityService::class)->check($source->path());

        $this->assertSame(EnvironmentCheckStatus::Passed, $result->status);
        Process::assertRan(fn ($process) => $process->path === $source->path()
            && $process->command === ['composer', 'check-platform-reqs', '--lock', '--no-dev']);
    }

    public function test_compatibility_check_fails_before_composer_when_release_files_are_missing(): void
    {
        Process::fake();

        $result = $this->app->make(UpdateCompatibilityService::class)->check($this->temporaryDirectory()->path());

        $this->assertSame(EnvironmentCheckStatus::Failed, $result->status);
        Process::assertNothingRan();
    }

    public function test_snapshot_captures_environment_release_metadata_and_sqlite_database(): void
    {
        $root = $this->temporaryDirectory();
        $environmentPath = $root->path('.env');
        $databasePath = $root->path('database.sqlite');
        File::put($environmentPath, "APP_ENV=testing\n");
        $database = new PDO('sqlite:' . $databasePath);
        $database->exec('CREATE TABLE settings (name TEXT NOT NULL)');
        $database->exec("INSERT INTO settings VALUES ('captured')");
        $database = null;
        config()->set([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => $databasePath,
        ]);

        $snapshot = $this->app->make(UpdateSnapshotService::class)->capture('v1.2.3', $root->path(), $environmentPath);

        $this->assertFileExists($snapshot->path . DIRECTORY_SEPARATOR . '.env');
        $this->assertFileExists($snapshot->path . DIRECTORY_SEPARATOR . 'composer.json');
        $this->assertFileExists($snapshot->path . DIRECTORY_SEPARATOR . 'composer.lock');
        $this->assertFileExists($snapshot->path . DIRECTORY_SEPARATOR . 'database.sqlite');
        $this->assertFileExists($snapshot->rollbackGuide);

        $metadata = File::json($snapshot->path . DIRECTORY_SEPARATOR . 'metadata.json');
        $this->assertSame('v1.2.3', $metadata['target_version']);
        $this->assertSame('sqlite', $metadata['database_driver']);
        $this->assertStringContainsString('php artisan p:environment:health', File::get($snapshot->rollbackGuide));
        $this->assertStringContainsString($snapshot->path . '/.env', File::get($snapshot->rollbackGuide));
    }

    public function test_snapshot_captures_committed_sqlite_wal_data(): void
    {
        $root = $this->temporaryDirectory();
        $environmentPath = $root->path('.env');
        $databasePath = $root->path('database.sqlite');
        File::put($environmentPath, "APP_ENV=testing\n");

        $database = new PDO('sqlite:' . $databasePath);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $database->exec('PRAGMA journal_mode = WAL');
        $database->exec('PRAGMA wal_autocheckpoint = 0');
        $database->exec('CREATE TABLE settings (name TEXT NOT NULL)');
        $database->exec("INSERT INTO settings VALUES ('committed in WAL')");
        $this->assertFileExists($databasePath . '-wal');

        config()->set([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => $databasePath,
        ]);

        $snapshot = $this->app->make(UpdateSnapshotService::class)->capture(null, $root->path(), $environmentPath);
        $backup = new PDO('sqlite:' . $snapshot->path . DIRECTORY_SEPARATOR . 'database.sqlite');

        $this->assertSame('committed in WAL', $backup->query('SELECT name FROM settings')->fetchColumn());
    }

    public function test_snapshot_capture_rejects_failed_required_copy(): void
    {
        $root = $this->temporaryDirectory();
        $environmentPath = $root->path('.env');
        File::put($environmentPath, "APP_ENV=testing\n");

        $files = new class extends Filesystem
        {
            public function copy($path, $target)
            {
                return false;
            }
        };
        $service = new UpdateSnapshotService($this->app->make(SoftwareVersionService::class), $files);

        try {
            $service->capture(null, $root->path(), $environmentPath);
            $this->fail('The snapshot should fail when a required file cannot be copied.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('could not be written', $exception->getMessage());
        }

        $this->assertSame([], File::directories($root->path()));
    }

    public function test_snapshot_capture_rejects_failed_required_write(): void
    {
        $root = $this->temporaryDirectory();
        $environmentPath = $root->path('.env');
        $databasePath = $root->path('database.sqlite');
        File::put($environmentPath, "APP_ENV=testing\n");
        $database = new PDO('sqlite:' . $databasePath);
        $database->exec('CREATE TABLE settings (name TEXT NOT NULL)');
        $database = null;
        config()->set([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => $databasePath,
        ]);

        $files = new class extends Filesystem
        {
            public function put($path, $contents, $lock = false)
            {
                return false;
            }
        };
        $service = new UpdateSnapshotService($this->app->make(SoftwareVersionService::class), $files);

        try {
            $service->capture(null, $root->path(), $environmentPath);
            $this->fail('The snapshot should fail when a required artifact cannot be written.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('could not be written', $exception->getMessage());
        }

        $this->assertSame([], File::directories($root->path()));
    }

    public function test_snapshot_loaders_reject_incomplete_snapshots(): void
    {
        $root = $this->temporaryDirectory();
        $snapshotPath = $root->path('20260818-120000-invalid');
        File::ensureDirectoryExists($snapshotPath);
        File::put($snapshotPath . DIRECTORY_SEPARATOR . 'ROLLBACK.md', '# Incomplete');

        $service = $this->app->make(UpdateSnapshotService::class);

        $this->assertNull($service->fromPath($snapshotPath));
        $this->assertNull($service->latest($root->path()));
    }

    private function temporaryDirectory(): TemporaryDirectory
    {
        $directory = TemporaryDirectory::make();
        $this->temporaryDirectories[] = $directory;

        return $directory;
    }
}
