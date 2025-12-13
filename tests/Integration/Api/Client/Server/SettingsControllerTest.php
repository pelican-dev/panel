<?php

namespace App\Tests\Integration\Api\Client\Server;

use App\Enums\ServerState;
use App\Enums\SubuserPermission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;

class SettingsControllerTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that the server's name can be changed.
     */
    #[DataProvider('renamePermissionsDataProvider')]
    public function test_server_name_can_be_changed(array $permissions): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount($permissions);
        $originalName = $server->name;

        $response = $this->actingAs($user)->postJson("/api/client/servers/$server->uuid/settings/rename", [
            'name' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.0.meta.rule', 'required');

        $server = $server->refresh();
        $this->assertSame($originalName, $server->name);

        $this->actingAs($user)
            ->postJson("/api/client/servers/$server->uuid/settings/rename", [
                'name' => 'Test Server Name',
            ])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $server = $server->refresh();
        $this->assertSame('Test Server Name', $server->name);
    }

    /**
     * Test that a subuser receives a permissions error if they do not have the required permission
     * and attempt to change the name.
     */
    public function test_subuser_cannot_change_server_name_without_permission(): void
    {
        [$user, $server] = $this->generateTestAccount([SubuserPermission::WebsocketConnect]);
        $originalName = $server->name;

        $this->actingAs($user)
            ->postJson("/api/client/servers/$server->uuid/settings/rename", [
                'name' => 'Test Server Name',
            ])
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $server = $server->refresh();
        $this->assertSame($originalName, $server->name);
    }

    /**
     * Test that a server can be reinstalled. Honestly this test doesn't do much of anything other
     * than make sure the endpoint works since.
     */
    #[DataProvider('reinstallPermissionsDataProvider')]
    public function test_server_can_be_reinstalled(array $permissions): void
    {
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount($permissions);
        $this->assertTrue($server->isInstalled());

        $service = \Mockery::mock(DaemonServerRepository::class);
        $this->app->instance(DaemonServerRepository::class, $service);

        $service->expects('setServer')
            ->with(\Mockery::on(function ($value) use ($server) {
                return $value->uuid === $server->uuid;
            }))
            ->andReturnSelf()
            ->getMock()
            ->expects('reinstall')
            ->andReturnUndefined();

        $this->actingAs($user)->postJson("/api/client/servers/$server->uuid/settings/reinstall")
            ->assertStatus(Response::HTTP_ACCEPTED);

        $server = $server->refresh();
        $this->assertSame(ServerState::Installing, $server->status);
    }

    /**
     * Test that a subuser receives a permissions error if they do not have the required permission
     * and attempt to reinstall a server.
     */
    public function test_subuser_cannot_reinstall_server_without_permission(): void
    {
        [$user, $server] = $this->generateTestAccount([SubuserPermission::WebsocketConnect]);

        $this->actingAs($user)
            ->postJson("/api/client/servers/$server->uuid/settings/reinstall")
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $server = $server->refresh();
        $this->assertTrue($server->isInstalled());
    }

    public static function renamePermissionsDataProvider(): array
    {
        return [[[]], [[SubuserPermission::SettingsRename]]];
    }

    public static function reinstallPermissionsDataProvider(): array
    {
        return [[[]], [[SubuserPermission::SettingsReinstall]]];
    }
}
