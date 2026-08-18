<?php

namespace App\Tests\Integration\Api\ContractFreeze\Application;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class ServerContractTest extends ContractFreezeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractAdmin();
    }

    public function test_index(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/servers'));
    }

    public function test_view(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/servers/100'));
    }

    public function test_view_with_all_includes(): void
    {
        // The transfer include is declared on the transformer but has no include
        // method, so requesting it is a 500 and it is left out here.
        $this->assertContractSnapshot($this->getJson('/api/application/servers/100?include=allocations,user,subusers,egg,variables,node,databases'));
    }

    public function test_view_external(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/servers/external/contract-external-1'));
    }

    public function test_databases_index(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/servers/100/databases'));
    }

    public function test_databases_index_with_includes(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/servers/100/databases?include=password,host'));
    }

    public function test_database_view(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/servers/100/databases/100'));
    }

    public function test_database_view_with_includes(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/servers/100/databases/100?include=password,host'));
    }
}
