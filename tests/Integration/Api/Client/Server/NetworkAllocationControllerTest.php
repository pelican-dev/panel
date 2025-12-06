<?php

namespace App\Tests\Integration\Api\Client\Server;

use App\Enums\SubuserPermission;
use App\Models\Allocation;
use App\Models\User;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;

class NetworkAllocationControllerTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that a servers allocations are returned in the expected format.
     */
    public function test_server_allocations_are_returned(): void
    {
        [$user, $server] = $this->generateTestAccount();

        $response = $this->actingAs($user)->getJson($this->link($server, '/network/allocations'));

        $response->assertOk();
        $response->assertJsonPath('object', 'list');
        $response->assertJsonCount(1, 'data');

        $this->assertJsonTransformedWith($response->json('data.0.attributes'), $server->allocation);
    }

    /**
     * Test that allocations cannot be returned without the required user permissions.
     */
    public function test_server_allocations_are_not_returned_without_permission(): void
    {
        [$user, $server] = $this->generateTestAccount();
        $user2 = User::factory()->create();

        $server->owner_id = $user2->id;
        $server->save();

        $this->actingAs($user)->getJson($this->link($server, '/network/allocations'))
            ->assertNotFound();

        [$user, $server] = $this->generateTestAccount([SubuserPermission::AllocationCreate]);

        $this->actingAs($user)->getJson($this->link($server, '/network/allocations'))
            ->assertForbidden();
    }

    /**
     * Tests that notes on an allocation can be set correctly.
     */
    #[DataProvider('updatePermissionsDataProvider')]
    public function test_allocation_notes_can_be_updated(array $permissions): void
    {
        [$user, $server] = $this->generateTestAccount($permissions);
        $allocation = $server->allocation;

        $this->assertNull($allocation->notes);

        $this->actingAs($user)->postJson($this->link($allocation), [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('errors.0.meta.rule', 'present');

        $this->actingAs($user)->postJson($this->link($allocation), ['notes' => 'Test notes'])
            ->assertOk()
            ->assertJsonPath('object', Allocation::RESOURCE_NAME)
            ->assertJsonPath('attributes.notes', 'Test notes');

        $allocation = $allocation->refresh();

        $this->assertSame('Test notes', $allocation->notes);

        $this->actingAs($user)->postJson($this->link($allocation), ['notes' => null])
            ->assertOk()
            ->assertJsonPath('object', Allocation::RESOURCE_NAME)
            ->assertJsonPath('attributes.notes', null);

        $allocation = $allocation->refresh();

        $this->assertNull($allocation->notes);
    }

    public function test_allocation_notes_cannot_be_updated_by_invalid_users(): void
    {
        [$user, $server] = $this->generateTestAccount();
        $user2 = User::factory()->create();

        $server->owner_id = $user2->id;
        $server->save();

        $this->actingAs($user)->postJson($this->link($server->allocation))->assertNotFound();

        [$user, $server] = $this->generateTestAccount([SubuserPermission::AllocationCreate]);

        $this->actingAs($user)->postJson($this->link($server->allocation))->assertForbidden();
    }

    #[DataProvider('updatePermissionsDataProvider')]
    public function test_primary_allocation_can_be_modified(array $permissions): void
    {
        [$user, $server] = $this->generateTestAccount($permissions);
        $allocation = $server->allocation;
        $allocation2 = Allocation::factory()->create(['node_id' => $server->node_id, 'server_id' => $server->id]);

        $server->allocation_id = $allocation->id;
        $server->save();

        $this->actingAs($user)->postJson($this->link($allocation2, '/primary'))
            ->assertOk();

        $server = $server->refresh();

        $this->assertSame($allocation2->id, $server->allocation_id);
    }

    public function test_primary_allocation_cannot_be_modified_by_invalid_user(): void
    {
        [$user, $server] = $this->generateTestAccount();
        $user2 = User::factory()->create();

        $server->owner_id = $user2->id;
        $server->save();

        $this->actingAs($user)->postJson($this->link($server->allocation, '/primary'))
            ->assertNotFound();

        [$user, $server] = $this->generateTestAccount([SubuserPermission::AllocationCreate]);

        $this->actingAs($user)->postJson($this->link($server->allocation, '/primary'))
            ->assertForbidden();
    }

    public static function updatePermissionsDataProvider(): array
    {
        return [[[]], [[SubuserPermission::AllocationUpdate]]];
    }
}
