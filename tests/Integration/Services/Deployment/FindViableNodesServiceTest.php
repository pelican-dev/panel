<?php

namespace App\Tests\Integration\Services\Deployment;

use App\Models\Node;
use App\Models\Server;
use App\Models\Database;
use App\Tests\Integration\IntegrationTestCase;
use App\Services\Deployment\FindViableNodesService;

class FindViableNodesServiceTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Database::query()->delete();
        Server::query()->delete();
        Node::query()->delete();
    }

    public function testExceptionIsThrownIfNoDiskSpaceHasBeenSet(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Disk space must be an int, got NULL');

        $this->getService()->handle();
    }

    public function testExceptionIsThrownIfNoMemoryHasBeenSet(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Memory usage must be an int, got NULL');

        $this->getService()->setDisk(10)->handle();
    }

    private function getService(): FindViableNodesService
    {
        return $this->app->make(FindViableNodesService::class);
    }
}
