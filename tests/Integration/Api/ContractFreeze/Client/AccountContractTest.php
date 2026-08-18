<?php

namespace App\Tests\Integration\Api\ContractFreeze\Client;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class AccountContractTest extends ContractFreezeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractOwner();
    }

    public function test_view(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/client/account'));
    }

    public function test_activity(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/client/account/activity'));
    }

    public function test_activity_with_actor(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/client/account/activity?include=actor'));
    }
}
