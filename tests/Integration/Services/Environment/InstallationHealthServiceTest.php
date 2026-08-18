<?php

namespace App\Tests\Integration\Services\Environment;

use App\Enums\EnvironmentCheckStatus;
use App\Models\Role;
use App\Models\User;
use App\Services\Environment\InstallationHealthService;
use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Support\Facades\Queue;

class InstallationHealthServiceTest extends IntegrationTestCase
{
    private InstallationHealthService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(InstallationHealthService::class);
    }

    public function test_sync_queue_probe_is_processed_immediately(): void
    {
        config()->set('queue.default', 'sync');

        $result = $this->service->queueWorker(1);

        $this->assertSame(EnvironmentCheckStatus::Passed, $result->status);
    }

    public function test_queue_probe_fails_when_a_worker_does_not_process_the_job(): void
    {
        Queue::fake();
        config()->set('queue.default', 'database');

        $result = $this->service->queueWorker(0);

        $this->assertSame(EnvironmentCheckStatus::Failed, $result->status);
        $this->assertNotNull($result->remediation);
    }

    public function test_complete_installation_checks_database_migrations_cache_admin_and_queue(): void
    {
        config()->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        config()->set('app.installed', true);
        config()->set('queue.default', 'sync');
        User::factory()->create()->syncRoles(Role::getRootAdmin());

        $results = collect($this->service->completeInstallation())->keyBy('key');

        foreach (['database', 'migrations', 'cache', 'queue', 'admin', 'app_key', 'installed'] as $key) {
            $this->assertSame(EnvironmentCheckStatus::Passed, $results->get($key)->status, "The {$key} check should pass.");
        }
    }

    public function test_selected_database_driver_requires_its_matching_pdo_extension(): void
    {
        $result = $this->service->databaseDriverExtension('sqlite');

        $this->assertSame(EnvironmentCheckStatus::Passed, $result->status);
        $this->assertStringContainsString('pdo_sqlite', $result->message);
    }

    public function test_application_key_must_have_a_supported_cipher_length(): void
    {
        config()->set('app.key', 'base64:' . base64_encode('too-short'));

        $result = $this->service->applicationKey();

        $this->assertSame(EnvironmentCheckStatus::Failed, $result->status);
    }
}
