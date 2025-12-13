<?php

namespace App\Tests\Integration\Api\Client\Server\Startup;

use App\Enums\SubuserPermission;
use App\Models\EggVariable;
use App\Models\User;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;

class UpdateStartupVariableTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that a startup variable can be edited successfully for a server.
     */
    #[DataProvider('permissionsDataProvider')]
    public function test_startup_variable_can_be_updated(array $permissions): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount($permissions);
        $server->fill([
            'startup' => 'java {{SERVER_JARFILE}} --version {{BUNGEE_VERSION}}',
        ])->save();

        $response = $this->actingAs($user)->putJson($this->link($server) . '/startup/variable', [
            'key' => 'BUNGEE_VERSION',
            'value' => '1.2.3',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.0.code', 'ValidationException');
        $response->assertJsonPath('errors.0.detail', 'The value may only contain letters and numbers.');

        $response = $this->actingAs($user)->putJson($this->link($server) . '/startup/variable', [
            'key' => 'BUNGEE_VERSION',
            'value' => '123',
        ]);

        $response->assertOk();
        $response->assertJsonPath('object', EggVariable::RESOURCE_NAME);
        $this->assertJsonTransformedWith($response->json('attributes'), $server->variables->firstWhere('env_variable', 'BUNGEE_VERSION'));
        $response->assertJsonPath('meta.startup_command', 'java bungeecord.jar --version 123');
        $response->assertJsonPath('meta.raw_startup_command', $server->startup);
    }

    /**
     * Test that variables that are either not user_viewable, or not user_editable, cannot be
     * updated via this endpoint.
     */
    #[DataProvider('permissionsDataProvider')]
    public function test_startup_variable_cannot_be_updated_if_not_user_viewable_or_editable(array $permissions): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount($permissions);

        $egg = $this->cloneEggAndVariables($server->egg);
        $egg->variables()->where('env_variable', 'BUNGEE_VERSION')->update(['user_viewable' => false]);
        $egg->variables()->where('env_variable', 'SERVER_JARFILE')->update(['user_editable' => false]);

        $server->fill(['egg_id' => $egg->id])->save();
        $server->refresh();

        $response = $this->actingAs($user)->putJson($this->link($server) . '/startup/variable', [
            'key' => 'BUNGEE_VERSION',
            'value' => '123',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('errors.0.code', 'BadRequestHttpException');
        $response->assertJsonPath('errors.0.detail', 'The environment variable you are trying to edit does not exist.');

        $response = $this->actingAs($user)->putJson($this->link($server) . '/startup/variable', [
            'key' => 'SERVER_JARFILE',
            'value' => 'server2.jar',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('errors.0.code', 'BadRequestHttpException');
        $response->assertJsonPath('errors.0.detail', 'The environment variable you are trying to edit is read-only.');
    }

    /**
     * Test that a hidden variable is not included in the startup_command output for the server if
     * a different variable is updated.
     */
    public function test_hidden_variables_are_not_returned_in_startup_command_when_updating_variable(): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount();

        $egg = $this->cloneEggAndVariables($server->egg);
        $egg->variables()->firstWhere('env_variable', 'BUNGEE_VERSION')->update(['user_viewable' => false]);

        $server->fill([
            'egg_id' => $egg->id,
            'startup' => 'java {{SERVER_JARFILE}} --version {{BUNGEE_VERSION}}',
        ])->save();

        $server->refresh();

        $response = $this->actingAs($user)->putJson($this->link($server) . '/startup/variable', [
            'key' => 'SERVER_JARFILE',
            'value' => 'server2.jar',
        ]);

        $response->assertOk();
        $response->assertJsonPath('meta.startup_command', 'java server2.jar --version [hidden]');
        $response->assertJsonPath('meta.raw_startup_command', $server->startup);
    }

    /**
     * Test that an egg variable with a validation rule of 'nullable|string' works if no value
     * is passed through in the request.
     */
    public function test_egg_variable_with_nullable_string_is_not_required(): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount();

        $egg = $this->cloneEggAndVariables($server->egg);
        $egg->variables()->firstWhere('env_variable', 'BUNGEE_VERSION')->update(['rules' => ['nullable', 'string']]);

        $server->fill(['egg_id' => $egg->id])->save();
        $server->refresh();

        $response = $this->actingAs($user)->putJson($this->link($server) . '/startup/variable', [
            'key' => 'BUNGEE_VERSION',
            'value' => '',
        ]);

        $response->assertOk();
        $response->assertJsonPath('attributes.server_value', null);
    }

    /**
     * Test that a variable cannot be updated if the user does not have permission to perform
     * that action, or they aren't assigned at all to the server.
     */
    public function test_startup_variable_cannot_be_updated_if_not_user_viewable(): void
    {
        [$user, $server] = $this->generateTestAccount([SubuserPermission::WebsocketConnect]);
        $this->actingAs($user)->putJson($this->link($server) . '/startup/variable')->assertForbidden();

        $user2 = User::factory()->create();
        $this->actingAs($user2)->putJson($this->link($server) . '/startup/variable')->assertNotFound();
    }

    public static function permissionsDataProvider(): array
    {
        return [[[]], [[SubuserPermission::StartupUpdate]]];
    }
}
