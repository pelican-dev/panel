<?php

namespace App\Tests\Integration\Api\ContractFreeze\Application;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class NodeContractTest extends ContractFreezeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractAdmin();
    }

    public function test_index(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/nodes'));
    }

    public function test_view(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/nodes/100'));
    }

    public function test_view_with_includes(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/nodes/100?include=allocations,servers'));
    }

    public function test_view_with_nested_include(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/nodes/100?include=servers.user'));
    }

    public function test_deployable(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/nodes/deployable?disk=100&memory=100'));
    }

    public function test_configuration(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/nodes/100/configuration'));
    }

    public function test_allocations_index(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/nodes/100/allocations'));
    }

    public function test_allocations_index_with_includes(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/nodes/100/allocations?include=node,server'));
    }
}
