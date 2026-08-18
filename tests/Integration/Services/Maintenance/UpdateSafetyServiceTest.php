<?php

namespace App\Tests\Integration\Services\Maintenance;

use App\Enums\EnvironmentCheckStatus;
use App\Services\Maintenance\UpdateCompatibilityService;
use App\Services\Maintenance\UpdateSnapshotService;
use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
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
        File::put($environmentPath, "APP_ENV=testing\n");

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
    }

    private function temporaryDirectory(): TemporaryDirectory
    {
        $directory = TemporaryDirectory::make();
        $this->temporaryDirectories[] = $directory;

        return $directory;
    }
}
