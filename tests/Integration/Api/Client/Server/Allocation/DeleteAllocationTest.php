<?php

namespace App\Tests\Integration\Api\Client\Server\Allocation;

use Illuminate\Http\Response;
use App\Models\Allocation;
use App\Models\Permission;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;

class DeleteAllocationTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that an allocation is deleted from the server and the notes are properly reset
     * to an empty value on assignment.
     *
     * @dataProvider permissionDataProvider
     */
    public function testAllocationCanBeDeletedFromServer(array $permission): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount($permission);
        $server->update(['allocation_limit' => 2]);

        /** @var \App\Models\Allocation $allocation */
        $allocation = Allocation::factory()->create([
            'server_id' => $server->id,
            'node_id' => $server->node_id,
            'notes' => 'hodor',
        ]);

        $this->actingAs($user)->deleteJson($this->link($allocation))->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('allocations', ['id' => $allocation->id, 'server_id' => null, 'notes' => null]);
    }

    /**
     * Test that an error is returned if the user does not have permissiont to delete an allocation.
     */
    public function testErrorIsReturnedIfUserDoesNotHavePermission(): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount([Permission::ACTION_ALLOCATION_CREATE]);

        /** @var \App\Models\Allocation $allocation */
        $allocation = Allocation::factory()->create([
            'server_id' => $server->id,
            'node_id' => $server->node_id,
            'notes' => 'hodor',
        ]);

        $this->actingAs($user)->deleteJson($this->link($allocation))->assertForbidden();

        $this->assertDatabaseHas('allocations', ['id' => $allocation->id, 'server_id' => $server->id]);
    }

    /**
     * Test that an allocation is not deleted if it is currently marked as the primary allocation
     * for the server.
     */
    public function testErrorIsReturnedIfAllocationIsPrimary(): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount();
        $server->update(['allocation_limit' => 2]);

        $this->actingAs($user)->deleteJson($this->link($server->allocation))
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonPath('errors.0.code', 'DisplayException')
            ->assertJsonPath('errors.0.detail', 'You cannot delete the primary allocation for this server.');
    }

    public function testAllocationCannotBeDeletedIfServerLimitIsNotDefined(): void
    {
        [$user, $server] = $this->generateTestAccount();

        /** @var \App\Models\Allocation $allocation */
        $allocation = Allocation::factory()->forServer($server)->create(['notes' => 'Test notes']);

        $this->actingAs($user)->deleteJson($this->link($allocation))
            ->assertStatus(400)
            ->assertJsonPath('errors.0.detail', 'You cannot delete allocations for this server: no allocation limit is set.');

        $allocation->refresh();
        $this->assertNotNull($allocation->notes);
        $this->assertEquals($server->id, $allocation->server_id);
    }

    /**
     * Test that an allocation cannot be deleted if it does not belong to the server instance.
     */
    public function testErrorIsReturnedIfAllocationDoesNotBelongToServer(): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount();
        [, $server2] = $this->generateTestAccount();

        $this->actingAs($user)->deleteJson($this->link($server2->allocation))->assertNotFound();
        $this->actingAs($user)->deleteJson($this->link($server, "/network/allocations/{$server2->allocation_id}"))->assertNotFound();
    }

    public static function permissionDataProvider(): array
    {
        return [[[Permission::ACTION_ALLOCATION_DELETE]], [[]]];
    }
}
