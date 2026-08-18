<?php

namespace App\Tests\Integration\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Tests\Integration\IntegrationTestCase;

class EnvironmentCommandsTest extends IntegrationTestCase
{
    public function test_preflight_can_verify_the_configured_database_and_queue_worker(): void
    {
        config()->set('queue.default', 'sync');

        $this->artisan('p:environment:preflight', [
            '--with-database' => true,
            '--with-queue' => true,
            '--queue-timeout' => 1,
        ])->assertExitCode(0);
    }

    public function test_health_command_fails_when_no_administrator_exists(): void
    {
        $this->configureInstalledApplication();

        $this->artisan('p:environment:health', ['--skip-queue' => true])
            ->assertExitCode(1);
    }

    public function test_health_command_passes_for_a_complete_installation(): void
    {
        $this->configureInstalledApplication();
        User::factory()->create()->syncRoles(Role::getRootAdmin());

        $this->artisan('p:environment:health', ['--skip-queue' => true])
            ->assertExitCode(0);
    }

    public function test_update_preparation_requires_an_extracted_target_release(): void
    {
        $this->artisan('p:maintenance:prepare-update')
            ->expectsOutput(trans('commands.update.source_required'))
            ->assertExitCode(1);
    }

    private function configureInstalledApplication(): void
    {
        config()->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        config()->set('app.installed', true);
        config()->set('queue.default', 'sync');
    }
}
