<?php

namespace App\Tests\Integration\Api\Client\Server;

use App\Enums\SubuserPermission;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;

class PowerControllerTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that a subuser without permission to send a command to the server receives
     * an error in response. This checks against the specific permission needed to send
     * the command to the server.
     *
     * @param  array<string|SubuserPermission>  $permissions
     */
    #[DataProvider('invalidPermissionDataProvider')]
    public function test_subuser_without_permissions_receives_error(string $action, array $permissions): void
    {
        [$user, $server] = $this->generateTestAccount($permissions);

        $this->actingAs($user)
            ->postJson("/api/client/servers/$server->uuid/power", ['signal' => $action])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test that sending an invalid power signal returns an error.
     */
    public function test_invalid_power_signal_results_in_error(): void
    {
        [$user, $server] = $this->generateTestAccount();

        $response = $this->actingAs($user)->postJson("/api/client/servers/$server->uuid/power", [
            'signal' => 'invalid',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.0.meta.rule', 'in');
        $response->assertJsonPath('errors.0.detail', 'The selected signal is invalid.');
    }

    /**
     * Test that sending a valid power actions works.
     */
    #[DataProvider('validPowerActionDataProvider')]
    public function test_action_can_be_sent_to_server(string $action, string|SubuserPermission $permission): void
    {
        $service = \Mockery::mock(DaemonServerRepository::class);
        $this->app->instance(DaemonServerRepository::class, $service);

        [$user, $server] = $this->generateTestAccount([$permission]);

        $service->expects('setServer')
            ->with(\Mockery::on(function ($value) use ($server) {
                return $server->uuid === $value->uuid;
            }))
            ->andReturnSelf()
            ->getMock()
            ->expects('power')
            ->with(trim($action));

        $this->actingAs($user)
            ->postJson("/api/client/servers/$server->uuid/power", ['signal' => $action])
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * Returns invalid permission combinations for a given power action.
     */
    public static function invalidPermissionDataProvider(): array
    {
        return [
            ['start', [SubuserPermission::ControlStop, SubuserPermission::ControlRestart]],
            ['stop', [SubuserPermission::ControlStart]],
            ['kill', [SubuserPermission::ControlStart, SubuserPermission::ControlRestart]],
            ['restart', [SubuserPermission::ControlStop, SubuserPermission::ControlStart]],
            ['random', [SubuserPermission::ControlStart]],
        ];
    }

    public static function validPowerActionDataProvider(): array
    {
        return [
            ['start', SubuserPermission::ControlStart],
            ['stop', SubuserPermission::ControlStop],
            ['restart', SubuserPermission::ControlRestart],
            ['kill', SubuserPermission::ControlStop],
            // Yes, these spaces are intentional. You should be able to send values with or without
            // a space on the start/end since we should be trimming the values.
            [' restart', SubuserPermission::ControlRestart],
            ['kill ', SubuserPermission::ControlStop],
        ];
    }
}
