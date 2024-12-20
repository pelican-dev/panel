<?php

namespace App\Tests\Integration\Services\Databases;

use Mockery\MockInterface;
use App\Models\Node;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Tests\Integration\IntegrationTestCase;
use App\Services\Databases\DatabaseManagementService;
use App\Services\Databases\DeployServerDatabaseService;
use App\Exceptions\Service\Database\NoSuitableDatabaseHostException;

class DeployServerDatabaseServiceTest extends IntegrationTestCase
{
    private MockInterface $managementService;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->managementService = \Mockery::mock(DatabaseManagementService::class);
        $this->swap(DatabaseManagementService::class, $this->managementService);
    }

    /**
     * Ensure we reset the config to the expected value.
     */
    protected function tearDown(): void
    {
        config()->set('panel.client_features.databases.allow_random', true);

        Database::query()->delete();
        DatabaseHost::query()->delete();

        parent::tearDown();
    }

    /**
     * Test that an error is thrown if either the database name or the remote host are empty.
     *
     * @dataProvider invalidDataProvider
     */
    public function testErrorIsThrownIfDatabaseNameIsEmpty(array $data): void
    {
        $server = $this->createServerModel();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Expected a non-empty value\. Got: /');
        $this->getService()->handle($server, $data);
    }

    /**
     * Test that an error is thrown if there are no database hosts on the same node as the
     * server and the allow_random config value is false.
     */
    public function testErrorIsThrownIfNoDatabaseHostsExistOnNode(): void
    {
        $server = $this->createServerModel();

        $node = Node::factory()->create();
        DatabaseHost::factory()->recycle($node)->create();

        config()->set('panel.client_features.databases.allow_random', false);

        $this->expectException(NoSuitableDatabaseHostException::class);

        $this->getService()->handle($server, [
            'database' => 'something',
            'remote' => '%',
        ]);
    }

    /**
     * Test that an error is thrown if no database hosts exist at all on the system.
     */
    public function testErrorIsThrownIfNoDatabaseHostsExistOnSystem(): void
    {
        $server = $this->createServerModel();

        $this->expectException(NoSuitableDatabaseHostException::class);

        $this->getService()->handle($server, [
            'database' => 'something',
            'remote' => '%',
        ]);
    }

    /**
     * Test that a database host on the same node as the server is preferred.
     */
    public function testDatabaseHostOnSameNodeIsPreferred(): void
    {
        $server = $this->createServerModel();
        $host = DatabaseHost::factory()->recycle($server->node)->create();

        $this->managementService->expects('create')->with($server, [
            'database_host_id' => $host->id,
            'database' => "s{$server->id}_something",
            'remote' => '%',
        ])->andReturns(new Database());

        $response = $this->getService()->handle($server, [
            'database' => 'something',
            'remote' => '%',
        ]);

        $this->assertInstanceOf(Database::class, $response);
    }

    /**
     * Test that a database host not assigned to the same node as the server is used if
     * there are no same-node hosts and the allow_random configuration value is set to
     * true.
     */
    public function testDatabaseHostIsSelectedIfNoSuitableHostExistsOnSameNode(): void
    {
        $server = $this->createServerModel();

        $node = Node::factory()->create();
        $host = DatabaseHost::factory()->recycle($node)->create();

        $this->managementService->expects('create')->with($server, [
            'database_host_id' => $host->id,
            'database' => "s{$server->id}_something",
            'remote' => '%',
        ])->andReturns(new Database());

        $response = $this->getService()->handle($server, [
            'database' => 'something',
            'remote' => '%',
        ]);

        $this->assertInstanceOf(Database::class, $response);
    }

    public static function invalidDataProvider(): array
    {
        return [
            [['remote' => '%']],
            [['database' => null, 'remote' => '%']],
            [['database' => '', 'remote' => '%']],
            [['database' => '']],
            [['database' => '', 'remote' => '']],
        ];
    }

    private function getService(): DeployServerDatabaseService
    {
        return $this->app->make(DeployServerDatabaseService::class);
    }
}
