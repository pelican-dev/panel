<?php

namespace App\Tests\Integration\Services\Servers;

use App\Enums\ServerState;
use App\Enums\SuspendAction;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Services\Servers\SuspensionService;
use App\Tests\Integration\IntegrationTestCase;
use Mockery\MockInterface;

class SuspensionServiceTest extends IntegrationTestCase
{
    private MockInterface $repository;

    /**
     * Setup test instance.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = \Mockery::mock(DaemonServerRepository::class);
        $this->app->instance(DaemonServerRepository::class, $this->repository);
    }

    public function test_server_is_suspended_and_unsuspended(): void
    {
        $server = $this->createServerModel();

        $this->repository->expects('setServer->sync')->twice()->andReturnSelf();

        $this->getService()->handle($server, SuspendAction::Suspend);

        $this->assertTrue($server->refresh()->isSuspended());

        $this->getService()->handle($server, SuspendAction::Unsuspend);

        $this->assertFalse($server->refresh()->isSuspended());
    }

    public function test_no_action_is_taken_if_suspension_status_is_unchanged(): void
    {
        $server = $this->createServerModel();

        $this->getService()->handle($server, SuspendAction::Unsuspend);

        $server->refresh();
        $this->assertFalse($server->isSuspended());

        $server->update(['status' => ServerState::Suspended]);
        $this->getService()->handle($server, SuspendAction::Suspend);

        $server->refresh();
        $this->assertTrue($server->isSuspended());
    }

    private function getService(): SuspensionService
    {
        return $this->app->make(SuspensionService::class);
    }
}
