<?php

namespace App\Tests\Integration\Services\Servers;

use App\Enums\ServerState;
use App\Enums\SuspendAction;
use Mockery\MockInterface;
use App\Services\Servers\SuspensionService;
use App\Tests\Integration\IntegrationTestCase;
use App\Repositories\Daemon\DaemonServerRepository;

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

    public function testServerIsSuspendedAndUnsuspended(): void
    {
        $server = $this->createServerModel();

        $this->repository->expects('setServer->sync')->twice()->andReturnSelf();

        $this->getService()->handle($server, SuspendAction::Suspend);

        $this->assertTrue($server->refresh()->isSuspended());

        $this->getService()->handle($server, SuspendAction::Unsuspend);

        $this->assertFalse($server->refresh()->isSuspended());
    }

    public function testNoActionIsTakenIfSuspensionStatusIsUnchanged(): void
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
