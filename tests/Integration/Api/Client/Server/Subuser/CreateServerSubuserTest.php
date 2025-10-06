<?php

namespace App\Tests\Integration\Api\Client\Server\Subuser;

use App\Models\Permission;
use App\Models\Subuser;
use App\Models\User;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;

class CreateServerSubuserTest extends ClientApiIntegrationTestCase
{
    use WithFaker;

    /**
     * Test that a subuser can be created for a server.
     */
    #[DataProvider('permissionsDataProvider')]
    public function test_subuser_can_be_created(array $permissions): void
    {
        [$user, $server] = $this->generateTestAccount($permissions);

        $response = $this->actingAs($user)->postJson($this->link($server) . '/users', [
            'email' => $email = $this->faker->email(),
            'permissions' => [
                Permission::ACTION_USER_CREATE,
            ],
        ]);

        $response->assertOk();

        /** @var \App\Models\User $subuser */
        $subuser = User::query()->where('email', $email)->firstOrFail();

        $response->assertJsonPath('object', Subuser::RESOURCE_NAME);
        $response->assertJsonPath('attributes.uuid', $subuser->uuid);
        $response->assertJsonPath('attributes.permissions', [
            Permission::ACTION_USER_CREATE,
            Permission::ACTION_WEBSOCKET_CONNECT,
        ]);

        $expected = $response->json('attributes');
        unset($expected['permissions']);

        $this->assertJsonTransformedWith($expected, $subuser);
    }

    /**
     * Tests that an error is returned if a subuser attempts to create a new subuser and assign
     * permissions that their account does not also possess.
     */
    public function test_error_is_returned_if_assigning_permissions_not_assigned_to_self(): void
    {
        [$user, $server] = $this->generateTestAccount([
            Permission::ACTION_USER_CREATE,
            Permission::ACTION_USER_READ,
            Permission::ACTION_CONTROL_CONSOLE,
        ]);

        $response = $this->actingAs($user)->postJson($this->link($server) . '/users', [
            'email' => $this->faker->email(),
            'permissions' => [
                Permission::ACTION_USER_CREATE,
                Permission::ACTION_USER_UPDATE, // This permission is not assigned to the subuser.
            ],
        ]);

        $response->assertForbidden();
        $response->assertJsonPath('errors.0.code', 'HttpForbiddenException');
        $response->assertJsonPath('errors.0.detail', 'Cannot assign permissions to a subuser that your account does not actively possess.');
    }

    /**
     * Throws some bad data at the API and ensures that a subuser cannot be created.
     */
    public function test_subuser_with_excessively_long_email_cannot_be_created(): void
    {
        [$user, $server] = $this->generateTestAccount();

        $email = str_repeat(Str::random(35), 7) . '@gmail.com'; // 255 is the hard limit for the column in MySQL.

        $response = $this->actingAs($user)->postJson($this->link($server) . '/users', [
            'email' => $email,
            'permissions' => [
                Permission::ACTION_USER_CREATE,
            ],
        ]);

        $response->assertOk();

        $response = $this->actingAs($user)->postJson($this->link($server) . '/users', [
            'email' => $email . '.au',
            'permissions' => [
                Permission::ACTION_USER_CREATE,
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.0.detail', 'The email must be between 1 and 255 characters.');
        $response->assertJsonPath('errors.0.meta.source_field', 'email');
    }

    /**
     * Test that creating a subuser when there is already an account with that email runs
     * as expected and does not create a new account.
     */
    public function test_creating_subuser_with_same_email_as_existing_user_works(): void
    {
        [$user, $server] = $this->generateTestAccount();

        /** @var \App\Models\User $existing */
        $existing = User::factory()->create(['email' => $this->faker->email()]);

        $response = $this->actingAs($user)->postJson($this->link($server) . '/users', [
            'email' => $existing->email,
            'permissions' => [
                Permission::ACTION_USER_CREATE,
            ],
        ]);

        $response->assertOk();
        $response->assertJsonPath('object', Subuser::RESOURCE_NAME);
        $response->assertJsonPath('attributes.uuid', $existing->uuid);
    }

    /**
     * Test that an error is returned if the account associated with an email address is already
     * associated with the server instance.
     */
    public function test_adding_subuser_that_already_is_assigned_returns_error(): void
    {
        [$user, $server] = $this->generateTestAccount();

        $response = $this->actingAs($user)->postJson($this->link($server) . '/users', [
            'email' => $email = $this->faker->email(),
            'permissions' => [
                Permission::ACTION_USER_CREATE,
            ],
        ]);

        $response->assertOk();

        $response = $this->actingAs($user)->postJson($this->link($server) . '/users', [
            'email' => $email,
            'permissions' => [
                Permission::ACTION_USER_CREATE,
            ],
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('errors.0.code', 'ServerSubuserExistsException');
        $response->assertJsonPath('errors.0.detail', 'A user with that email address is already assigned as a subuser for this server.');
    }

    public static function permissionsDataProvider(): array
    {
        return [[[]], [[Permission::ACTION_USER_CREATE]]];
    }
}
