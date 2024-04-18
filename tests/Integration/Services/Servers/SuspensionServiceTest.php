<?php

namespace App\Tests\Integration\Services\Servers;

use App\Enums\ServerState;
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

        $this->getService()->toggle($server);

        $this->assertTrue($server->refresh()->isSuspended());

        $this->getService()->toggle($server, SuspensionService::ACTION_UNSUSPEND);

        $this->assertFalse($server->refresh()->isSuspended());
    }

    public function testNoActionIsTakenIfSuspensionStatusIsUnchanged(): void
    {
        $server = $this->createServerModel();

        $this->getService()->toggle($server, SuspensionService::ACTION_UNSUSPEND);

        $server->refresh();
        $this->assertFalse($server->isSuspended());

        $server->update(['status' => ServerState::Suspended]);
        $this->getService()->toggle($server);

        $server->refresh();
        $this->assertTrue($server->isSuspended());
    }

    public function testExceptionIsThrownIfInvalidActionsArePassed(): void
    {
        $server = $this->createServerModel();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected one of: "suspend", "unsuspend". Got: "foo"');

        $this->getService()->toggle($server, 'foo');
    }

    private function getService(): SuspensionService
    {
        return $this->app->make(SuspensionService::class);
    }
}
