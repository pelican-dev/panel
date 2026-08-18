<?php

namespace App\Tests\Integration\Api\ContractFreeze\Client\Server;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class ServerContractTest extends ContractFreezeTestCase
{
    private const BASE = '/api/client/servers/00000000-0000-4000-8000-000000000400';

    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractOwner();
    }

    public function test_view(): void
    {
        $this->assertContractSnapshot($this->getJson(self::BASE));
    }

    public function test_view_with_all_includes(): void
    {
        $this->assertContractSnapshot($this->getJson(self::BASE . '?include=egg,subusers'));
    }

    public function test_view_as_subuser(): void
    {
        $this->actingAsContractSubuser();

        $this->assertContractSnapshot($this->getJson(self::BASE));
    }

    public function test_view_without_editable_descriptions(): void
    {
        config()->set('panel.editable_server_descriptions', false);

        $this->assertContractSnapshot($this->getJson(self::BASE));
    }
}
