<?php

namespace App\Tests\Integration\Services\Servers;

use Mockery\MockInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use App\Models\Server;
use GuzzleHttp\Exception\RequestException;
use App\Tests\Integration\IntegrationTestCase;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Services\Servers\BuildModificationService;
use App\Exceptions\Http\Connection\DaemonConnectionException;

class BuildModificationServiceTest extends IntegrationTestCase
{
    private MockInterface $daemonServerRepository;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->daemonServerRepository = $this->mock(DaemonServerRepository::class);
    }

    /**
     * Test that the build data for the server is properly passed along to the daemon instance so that
     * the server data is updated in realtime. This test also ensures that only certain fields get updated
     * for the server, and not just any arbitrary field.
     */
    public function testServerBuildDataIsProperlyUpdatedOndaemon(): void
    {
        $server = $this->createServerModel();

        $this->daemonServerRepository->expects('setServer')->with(\Mockery::on(function (Server $s) use ($server) {
            return $s->id === $server->id;
        }))->andReturnSelf();

        $this->daemonServerRepository->expects('sync')->withNoArgs()->andReturnUndefined();

        $response = $this->getService()->handle($server, [
            'oom_killer' => false,
            'memory' => 256,
            'swap' => 128,
            'io' => 600,
            'cpu' => 150,
            'threads' => '1,2',
            'disk' => 1024,
            'backup_limit' => null,
            'database_limit' => 10,
            'allocation_limit' => 20,
        ]);

        $this->assertFalse($response->oom_killer);
        $this->assertSame(256, $response->memory);
        $this->assertSame(128, $response->swap);
        $this->assertSame(600, $response->io);
        $this->assertSame(150, $response->cpu);
        $this->assertSame('1,2', $response->threads);
        $this->assertSame(1024, $response->disk);
        $this->assertSame(0, $response->backup_limit);
        $this->assertSame(10, $response->database_limit);
        $this->assertSame(20, $response->allocation_limit);
    }

    /**
     * Test that an exception when connecting to the Daemon instance is properly ignored
     * when making updates. This allows for a server to be modified even when the Daemon
     * node is offline.
     */
    public function testConnectionExceptionIsIgnoredWhenUpdatingServerSettings(): void
    {
        $this->markTestSkipped();

        $server = $this->createServerModel();

        $this->daemonServerRepository->expects('setServer->sync')->andThrows(
            new DaemonConnectionException(
                new RequestException('Bad request', new Request('GET', '/test'), new Response())
            )
        );

        $response = $this->getService()->handle($server, ['memory' => 256, 'disk' => 10240]);

        $this->assertInstanceOf(Server::class, $response);
        $this->assertSame(256, $response->memory);
        $this->assertSame(10240, $response->disk);

        $this->assertDatabaseHas('servers', ['id' => $response->id, 'memory' => 256, 'disk' => 10240]);
    }

    private function getService(): BuildModificationService
    {
        return $this->app->make(BuildModificationService::class);
    }
}
