<?php

namespace App\Tests\Integration\Api\ContractFreeze\Client\Server;

use App\Repositories\Daemon\DaemonServerRepository;
use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class ResourceUtilizationContractTest extends ContractFreezeTestCase
{
    private const BASE = '/api/client/servers/00000000-0000-4000-8000-000000000400';

    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractOwner();

        $mock = \Mockery::mock(DaemonServerRepository::class);
        $this->app->instance(DaemonServerRepository::class, $mock);

        $mock->allows('setServer')->andReturnSelf();
        $mock->allows('getDetails')->andReturns([
            'state' => 'running',
            'is_suspended' => false,
            'utilization' => [
                'memory_bytes' => 536870912,
                'cpu_absolute' => 12,
                'disk_bytes' => 1073741824,
                'network' => [
                    'rx_bytes' => 1024,
                    'tx_bytes' => 2048,
                ],
                'uptime' => 60000,
            ],
        ]);
    }

    public function test_resources(): void
    {
        $this->assertContractSnapshot($this->getJson(self::BASE . '/resources'));
    }
}
