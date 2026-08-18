<?php

namespace App\Tests\Integration\Api\ContractFreeze\Application;

use App\Models\ApiKey;
use App\Models\User;
use App\Services\Acl\Api\AdminAcl;
use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class UserContractTest extends ContractFreezeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractAdmin();
    }

    public function test_index(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/users'));
    }

    public function test_index_paginated(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/users?per_page=1&page=2'));
    }

    public function test_view(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/users/101'));
    }

    public function test_view_with_all_includes(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/users/101?include=servers,roles'));
    }

    public function test_view_external(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/users/external/contract-owner-ext'));
    }

    public function test_denied_include_returns_null_resource(): void
    {
        $this->createNewReadOnlyUsersKey();

        $this->assertContractSnapshot($this->getJson('/api/application/users/101?include=servers,roles'));
    }

    private function createNewReadOnlyUsersKey(): void
    {
        $key = ApiKey::factory()->create([
            'id' => 110,
            'user_id' => $this->contractAdmin->id,
            'key_type' => ApiKey::TYPE_APPLICATION,
            'identifier' => 'papp_contract002',
            'token' => 'contractfreezeapplicationtoken02',
            'memo' => 'Contract freeze reduced key',
            'permissions' => [
                User::RESOURCE_NAME => AdminAcl::READ,
            ],
        ]);

        $this->withHeader('Authorization', 'Bearer ' . $key->identifier . 'contractfreezeapplicationtoken02');
    }
}
