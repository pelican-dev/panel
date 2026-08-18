<?php

namespace App\Tests\Filament\Installer;

use App\Enums\EnvironmentCheckStatus;
use App\Livewire\Installer\PanelInstaller;
use App\Models\User;
use App\Services\Environment\InstallationHealthService;
use App\Services\Users\UserCreationService;
use App\Tests\Integration\IntegrationTestCase;
use App\ValueObjects\EnvironmentCheckResult;
use Mockery;

class PanelInstallerTest extends IntegrationTestCase
{
    public function test_submit_verifies_the_queue_before_creating_the_admin_and_marks_the_install_complete_last(): void
    {
        $user = User::factory()->create();
        $creationService = Mockery::mock(UserCreationService::class);
        $health = Mockery::mock(InstallationHealthService::class);
        $installer = Mockery::mock(PanelInstaller::class)->makePartial();

        $installer->shouldReceive('runMigrations')->once()->ordered();
        $installer->shouldReceive('verifyQueueWorker')->once()->with($health)->ordered();
        $installer->shouldReceive('createAdminUser')->once()->with($creationService)->andReturn($user)->ordered();
        $installer->shouldReceive('writeToEnv')->once()->with('env_session')->ordered();
        $installer->shouldReceive('installEggs')->once()->ordered();
        $installer->shouldReceive('writeToEnvironment')->once()->with(['APP_INSTALLED' => 'true'])->ordered();
        $installer->shouldReceive('redirect')->once()->withAnyArgs()->ordered();

        $health->shouldReceive('completeInstallation')->once()->with(false)->andReturn([]);
        $health->shouldReceive('hasFailures')->once()->with([])->andReturnFalse();

        $installer->submit($creationService, $health);

        $this->assertTrue((bool) config('app.installed'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_submit_stops_before_admin_creation_when_the_queue_worker_is_unavailable(): void
    {
        $creationService = Mockery::mock(UserCreationService::class);
        $health = Mockery::mock(InstallationHealthService::class);
        $installer = Mockery::mock(PanelInstaller::class)->makePartial();

        $installer->shouldReceive('runMigrations')->once();
        $installer->shouldReceive('createAdminUser')->never();
        $health->shouldReceive('queueWorker')->once()->andReturn(new EnvironmentCheckResult(
            'queue',
            'Queue worker',
            EnvironmentCheckStatus::Failed,
            'The probe timed out.',
            'Start the worker.',
        ));

        $installer->submit($creationService, $health);

        $this->assertGuest();
    }
}
