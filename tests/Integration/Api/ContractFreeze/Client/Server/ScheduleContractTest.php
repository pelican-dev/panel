<?php

namespace App\Tests\Integration\Api\ContractFreeze\Client\Server;

use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class ScheduleContractTest extends ContractFreezeTestCase
{
    private const BASE = '/api/client/servers/00000000-0000-4000-8000-000000000400';

    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        $this->actingAsContractOwner();
    }

    public function test_index(): void
    {
        $this->assertContractSnapshot($this->getJson(self::BASE . '/schedules'));
    }

    public function test_view(): void
    {
        $this->assertContractSnapshot($this->getJson(self::BASE . '/schedules/100'));
    }

    public function test_view_with_tasks(): void
    {
        $this->assertContractSnapshot($this->getJson(self::BASE . '/schedules/100?include=tasks'));
    }
}
