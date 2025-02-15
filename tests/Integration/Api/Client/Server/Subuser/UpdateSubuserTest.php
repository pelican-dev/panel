<?php

namespace App\Tests\Integration\Api\Client\Server\Subuser;

use App\Models\User;
use App\Models\Subuser;
use App\Models\Permission;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Support\Facades\Http;

class UpdateSubuserTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that the correct permissions are applied to the account when making updates
     * to a subusers permissions.
     */
    public function test_correct_permissions_are_required_for_updating(): void
    {
        [$user, $server] = $this->generateTestAccount(['user.read']);

        Http::fake();

        $subuser = Subuser::factory()
            ->for(User::factory()->create())
            ->for($server)
            ->create([
                'permissions' => ['control.start'],
            ]);

        $this->postJson(
            $endpoint = "/api/client/servers/$server->uuid/users/{$subuser->user->uuid}",
            $data = [
                'permissions' => [
                    'control.start',
                    'control.stop',
                ],
            ]
        )
            ->assertUnauthorized();

        $this->actingAs($subuser->user)->postJson($endpoint, $data)->assertForbidden();
        $this->actingAs($user)->postJson($endpoint, $data)->assertForbidden();

        $server->subusers()->where('user_id', $user->id)->update([
            'permissions' => [
                Permission::ACTION_USER_UPDATE,
                Permission::ACTION_CONTROL_START,
                Permission::ACTION_CONTROL_STOP,
            ],
        ]);

        $this->postJson($endpoint, $data)->assertOk();
    }

    /**
     * Tests that permissions for the account are updated and any extraneous values
     * we don't know about are removed.
     */
    public function test_permissions_are_saved_to_account(): void
    {
        [$user, $server] = $this->generateTestAccount();

        /** @var \App\Models\Subuser $subuser */
        $subuser = Subuser::factory()
            ->for(User::factory()->create())
            ->for($server)
            ->create([
                'permissions' => ['control.restart', 'websocket.connect', 'foo.bar'],
            ]);

        Http::fake();

        $this->actingAs($user)
            ->postJson("/api/client/servers/$server->uuid/users/{$subuser->user->uuid}", [
                'permissions' => [
                    'control.start',
                    'control.stop',
                    'control.stop',
                    'foo.bar',
                    'power.fake',
                ],
            ])
            ->assertOk();

        $subuser->refresh();
        $this->assertEqualsCanonicalizing(
            ['control.start', 'control.stop', 'websocket.connect'],
            $subuser->permissions
        );
    }

    /**
     * Ensure a subuser cannot assign permissions to an account that they do not have
     * themselves.
     */
    public function test_user_cannot_assign_permissions_they_do_not_have(): void
    {
        [$user, $server] = $this->generateTestAccount([Permission::ACTION_USER_READ, Permission::ACTION_USER_UPDATE]);

        $subuser = Subuser::factory()
            ->for(User::factory()->create())
            ->for($server)
            ->create(['permissions' => ['foo.bar']]);

        $this->actingAs($user)
            ->postJson("/api/client/servers/$server->uuid/users/{$subuser->user->uuid}", [
                'permissions' => [Permission::ACTION_USER_READ, Permission::ACTION_CONTROL_CONSOLE],
            ])
            ->assertForbidden();

        $this->assertEqualsCanonicalizing(['foo.bar'], $subuser->refresh()->permissions);
    }

    /**
     * Test that a user cannot update thyself.
     */
    public function test_user_cannot_update_self(): void
    {
        [$user, $server] = $this->generateTestAccount([Permission::ACTION_USER_READ, Permission::ACTION_USER_UPDATE]);

        $this->actingAs($user)
            ->postJson("/api/client/servers/$server->uuid/users/$user->uuid", [])
            ->assertForbidden();
    }

    /**
     * Test that an error is returned if you attempt to update a subuser on a different account.
     */
    public function test_cannot_update_subuser_for_different_server(): void
    {
        [$user, $server] = $this->generateTestAccount();
        [$user2] = $this->generateTestAccount(['foo.bar']);

        $this->actingAs($user)
            ->postJson("/api/client/servers/$server->uuid/users/$user2->uuid", [])
            ->assertNotFound();
    }
}
