<?php

namespace App\Tests\Integration\Api\ContractFreeze\Application;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class DatabaseHostContractTest extends ContractFreezeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractAdmin();
    }

    public function test_index(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/database-hosts'));
    }

    public function test_view(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/database-hosts/100'));
    }

    public function test_view_with_includes(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/database-hosts/100?include=databases,nodes'));
    }
}
