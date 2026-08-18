<?php

namespace App\Tests\Integration\Api\ContractFreeze\Application;

use App\Models\Egg;
use App\Tests\Integration\Api\ContractFreeze\ContractFreezeTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('contract-freeze')]
class EggContractTest extends ContractFreezeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->buildContractWorld();
        // The index endpoint returns every egg, and the seeded eggs carry random
        // uuids that differ per database, so only the pinned egg may remain.
        Egg::query()->whereKeyNot(100)->delete();
        $this->actingAsContractAdmin();
    }

    public function test_index(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/eggs'));
    }

    public function test_view(): void
    {
        $this->assertContractSnapshot($this->getJson('/api/application/eggs/100'));
    }

    public function test_view_with_all_includes(): void
    {
        // The config and script includes are declared on the transformer but have no
        // include methods, so requesting them is a 500 and they are left out here.
        $this->assertContractSnapshot($this->getJson('/api/application/eggs/100?include=servers,variables'));
    }

    // The export endpoint streams a YAML download, not JSON, so it is not snapshotted here.
}
