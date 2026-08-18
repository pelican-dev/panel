<?php

namespace App\Tests\Integration\Api\ContractFreeze\Application;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class MountContractTest extends ContractFreezeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractAdmin();
    }

    public function test_index(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/mounts'));
    }

    public function test_view(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/mounts/100'));
    }

    public function test_view_with_includes(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/mounts/100?include=eggs,nodes,servers'));
    }

    public function test_eggs(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/mounts/100/eggs'));
    }

    public function test_nodes(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/mounts/100/nodes'));
    }

    public function test_servers(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/mounts/100/servers'));
    }
}
