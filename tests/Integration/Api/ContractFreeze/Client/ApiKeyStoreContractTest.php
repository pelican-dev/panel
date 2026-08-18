<?php

namespace App\Tests\Integration\Api\ContractFreeze\Client;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class ApiKeyStoreContractTest extends ContractFreezeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractOwner();

        // The identifier and the meta.secret_token both come from Str::random, so pin
        // the generator to a fixed character before the request is made.
        Str::createRandomStringsUsing(fn ($length) => str_repeat('k', $length));
    }

    protected function tearDown(): void
    {
        Str::createRandomStringsUsing();

        parent::tearDown();
    }

    public function test_store(): void
    {
        $this->assertContractSnapshot($this->postJson('/api/client/account/api-keys', [
            'description' => 'Contract mutation key',
            'allowed_ips' => ['192.0.2.1'],
        ]));
    }
}
