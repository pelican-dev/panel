<?php

namespace App\Tests\Integration\Api\ContractFreeze\Client;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class ClientContractTest extends ContractFreezeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();

        // The factory stores rules as a pipe string but the model casts it to array,

        $this->actingAsContractOwner();
    }

    public function test_index(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/client'));
    }

    public function test_index_paginated(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/client?per_page=1&page=1'));
    }

    public function test_permissions(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/client/permissions'));
    }
}
