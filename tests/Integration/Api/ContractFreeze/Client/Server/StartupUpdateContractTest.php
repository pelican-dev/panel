<?php

namespace App\Tests\Integration\Api\ContractFreeze\Client\Server;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class StartupUpdateContractTest extends ContractFreezeTestCase
{
    private const BASE = '/api/client/servers/00000000-0000-4000-8000-000000000400';

    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractOwner();
    }

    public function test_update_variable(): void
    {
        $this->assertContractSnapshot($this->putJson(self::BASE . '/startup/variable', [
            'key' => 'SERVER_JARFILE',
            'value' => 'frozen.jar',
        ]));
    }
}
