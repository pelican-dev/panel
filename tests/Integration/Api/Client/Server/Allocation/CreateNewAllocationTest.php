<?php

namespace App\Tests\Integration\Api\Client\Server\Allocation;

use App\Enums\SubuserPermission;
use App\Models\Allocation;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;

class CreateNewAllocationTest extends ClientApiIntegrationTestCase
{
    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('panel.client_features.allocations.enabled', true);
        config()->set('panel.client_features.allocations.range_start', 5000);
        config()->set('panel.client_features.allocations.range_end', 5050);
    }

    /**
     * Tests that a new allocation can be properly assigned to a server.
     */
    #[DataProvider('permissionDataProvider')]
    public function test_new_allocation_can_be_assigned_to_server(array $permission): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount($permission);
        $server->update(['allocation_limit' => 2]);

        $response = $this->actingAs($user)->postJson($this->link($server, '/network/allocations'));
        $response->assertJsonPath('object', Allocation::RESOURCE_NAME);

        $matched = Allocation::query()->findOrFail($response->json('attributes.id'));

        $this->assertSame($server->id, $matched->server_id);
        $this->assertJsonTransformedWith($response->json('attributes'), $matched);
    }

    /**
     * Test that a user without the required permissions cannot create an allocation for
     * the server instance.
     */
    public function test_allocation_cannot_be_created_if_user_does_not_have_permission(): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount([SubuserPermission::AllocationUpdate]);
        $server->update(['allocation_limit' => 2]);

        $this->actingAs($user)->postJson($this->link($server, '/network/allocations'))->assertForbidden();
    }

    /**
     * Test that an error is returned to the user if this feature is not enabled on the system.
     */
    public function test_allocation_cannot_be_created_if_not_enabled(): void
    {
        config()->set('panel.client_features.allocations.enabled', false);

        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount();
        $server->update(['allocation_limit' => 2]);

        $this->actingAs($user)->postJson($this->link($server, '/network/allocations'))
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonPath('errors.0.code', 'AutoAllocationNotEnabledException')
            ->assertJsonPath('errors.0.detail', 'Server auto-allocation is not enabled for this instance.');
    }

    /**
     * Test that an allocation cannot be created if the server has reached its allocation limit.
     */
    public function test_allocation_cannot_be_created_if_server_is_at_limit(): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount();
        $server->update(['allocation_limit' => 1]);

        $this->actingAs($user)->postJson($this->link($server, '/network/allocations'))
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonPath('errors.0.code', 'DisplayException')
            ->assertJsonPath('errors.0.detail', 'Cannot assign additional allocations to this server: limit has been reached.');
    }

    public static function permissionDataProvider(): array
    {
        return [[[SubuserPermission::AllocationCreate]], [[]]];
    }
}
