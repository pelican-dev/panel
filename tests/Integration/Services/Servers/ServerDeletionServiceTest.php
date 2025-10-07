<?php

namespace App\Tests\Integration\Services\Servers;

use App\Models\Database;
use App\Models\DatabaseHost;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Services\Databases\DatabaseManagementService;
use App\Services\Servers\ServerDeletionService;
use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Http\Client\ConnectionException;
use Mockery\MockInterface;

class ServerDeletionServiceTest extends IntegrationTestCase
{
    private MockInterface $daemonServerRepository;

    private MockInterface $databaseManagementService;

    private static ?string $defaultLogger;

    /**
     * Stub out services that we don't want to test in here.
     */
    protected function setUp(): void
    {
        parent::setUp();

        self::$defaultLogger = config('logging.default');
        // There will be some log calls during this test, don't actually write to the disk.
        config()->set('logging.default', 'null');

        $this->daemonServerRepository = \Mockery::mock(DaemonServerRepository::class);
        $this->databaseManagementService = \Mockery::mock(DatabaseManagementService::class);

        $this->app->instance(DaemonServerRepository::class, $this->daemonServerRepository);
        $this->app->instance(DatabaseManagementService::class, $this->databaseManagementService);
    }

    /**
     * Reset the log driver.
     */
    protected function tearDown(): void
    {
        config()->set('logging.default', self::$defaultLogger);
        self::$defaultLogger = null;

        parent::tearDown();
    }

    /**
     * Test that a server is not deleted if the force option is not set and an error
     * is returned by daemon.
     */
    public function test_regular_delete_fails_if_daemon_returns_error(): void
    {
        $server = $this->createServerModel();

        $this->expectException(ConnectionException::class);

        $this->daemonServerRepository->expects('setServer->delete')->withNoArgs()->andThrows(new ConnectionException());

        $this->getService()->handle($server);

        $this->assertDatabaseHas('servers', ['id' => $server->id]);
    }

    /**
     * Test that a 404 from Daemon while deleting a server does not cause the deletion to fail.
     */
    public function test_regular_delete_ignores404_from_daemon(): void
    {
        $server = $this->createServerModel();

        $this->daemonServerRepository->expects('setServer->delete')->withNoArgs()->andThrows(new ConnectionException(code: 404));

        $this->getService()->handle($server);

        $this->assertDatabaseMissing('servers', ['id' => $server->id]);
    }

    /**
     * Test that an error from Daemon does not cause the deletion to fail if the server is being
     * force deleted.
     */
    public function test_force_delete_ignores_exception_from_daemon(): void
    {
        $server = $this->createServerModel();

        $this->daemonServerRepository->expects('setServer->delete')->withNoArgs()->andThrows(new ConnectionException());

        $this->getService()->withForce()->handle($server);

        $this->assertDatabaseMissing('servers', ['id' => $server->id]);
    }

    /**
     * Test that a non-force-delete call does not delete the server if one of the databases
     * cannot be deleted from the host.
     */
    public function test_exception_while_deleting_stops_process(): void
    {
        $server = $this->createServerModel();
        $host = DatabaseHost::factory()->create();

        /** @var \App\Models\Database $db */
        $db = Database::factory()->create(['database_host_id' => $host->id, 'server_id' => $server->id]);

        $server->refresh();

        $this->daemonServerRepository->expects('setServer->delete')->withNoArgs()->andReturnUndefined();
        $this->databaseManagementService->expects('delete')->with(\Mockery::on(function ($value) use ($db) {
            return $value instanceof Database && $value->id === $db->id;
        }))->andThrows(new \Exception());

        $this->expectException(\Exception::class);
        $this->getService()->handle($server);

        $this->assertDatabaseHas('servers', ['id' => $server->id]);
        $this->assertDatabaseHas('databases', ['id' => $db->id]);
    }

    /**
     * Test that a server is deleted even if the server databases cannot be deleted from the host.
     */
    public function test_exception_while_deleting_databases_does_not_abort_if_force_deleted(): void
    {
        $server = $this->createServerModel();
        $host = DatabaseHost::factory()->create();

        /** @var \App\Models\Database $db */
        $db = Database::factory()->create(['database_host_id' => $host->id, 'server_id' => $server->id]);

        $server->refresh();

        $this->daemonServerRepository->expects('setServer->delete')->withNoArgs()->andReturnUndefined();
        $this->databaseManagementService->expects('delete')->with(\Mockery::on(function ($value) use ($db) {
            return $value instanceof Database && $value->id === $db->id;
        }))->andThrows(new \Exception());

        $this->getService()->withForce(true)->handle($server);

        $this->assertDatabaseMissing('servers', ['id' => $server->id]);
        $this->assertDatabaseMissing('databases', ['id' => $db->id]);
    }

    private function getService(): ServerDeletionService
    {
        return $this->app->make(ServerDeletionService::class);
    }
}
