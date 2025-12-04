<?php

namespace App\Tests\Integration\Api\Client\Server\Startup;

use App\Enums\SubuserPermission;
use App\Models\EggVariable;
use App\Models\User;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GetStartupAndVariablesTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that the startup command and variables are returned for a server, but only the variables
     * that can be viewed by a user (e.g. user_viewable=true).
     */
    #[DataProvider('permissionsDataProvider')]
    public function test_startup_variables_are_returned_for_server(array $permissions): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount($permissions);

        $egg = $this->cloneEggAndVariables($server->egg);
        // BUNGEE_VERSION should never be returned to the user in this API call, either in
        // the array of variables, or revealed in the startup command.
        $egg->variables()->firstWhere('env_variable', 'BUNGEE_VERSION')->update([
            'user_viewable' => false,
        ]);

        $server->fill([
            'egg_id' => $egg->id,
            'startup' => 'java {{SERVER_JARFILE}} --version {{BUNGEE_VERSION}}',
        ])->save();
        $server = $server->refresh();

        $response = $this->actingAs($user)->getJson($this->link($server) . '/startup');

        $response->assertOk();
        $response->assertJsonPath('meta.startup_command', 'java bungeecord.jar --version [hidden]');
        $response->assertJsonPath('meta.raw_startup_command', $server->startup);

        $response->assertJsonPath('object', 'list');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.object', EggVariable::RESOURCE_NAME);
        $this->assertJsonTransformedWith($response->json('data.0.attributes'), $egg->variables()->where('user_viewable', true)->first());
    }

    /**
     * Test that a user without the required permission, or who does not have any permission to
     * access the server cannot get the startup information for it.
     */
    public function test_startup_data_is_not_returned_without_permission(): void
    {
        [$user, $server] = $this->generateTestAccount([SubuserPermission::WebsocketConnect]);
        $this->actingAs($user)->getJson($this->link($server) . '/startup')->assertForbidden();

        $user2 = User::factory()->create();
        $this->actingAs($user2)->getJson($this->link($server) . '/startup')->assertNotFound();
    }

    public static function permissionsDataProvider(): array
    {
        return [[[]], [[SubuserPermission::StartupRead]]];
    }
}
