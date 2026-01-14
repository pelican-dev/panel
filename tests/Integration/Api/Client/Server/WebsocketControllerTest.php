<?php

namespace App\Tests\Integration\Api\Client\Server;

use App\Enums\SubuserPermission;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Carbon\CarbonImmutable;
use Illuminate\Http\Response;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class WebsocketControllerTest extends ClientApiIntegrationTestCase
{
    public function test_subuser_without_websocket_permission_receives_error(): void
    {
        [$user, $server] = $this->generateTestAccount([SubuserPermission::ControlRestart]);

        $this->actingAs($user)->getJson("/api/client/servers/$server->uuid/websocket")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonPath('errors.0.code', 'HttpForbiddenException')
            ->assertJsonPath('errors.0.detail', 'You do not have permission to connect to this server\'s websocket.');
    }

    /**
     * Confirm users cannot access the websocket for another user's server.
     */
    public function test_user_without_permission_for_server_receives_error(): void
    {
        [, $server] = $this->generateTestAccount([SubuserPermission::WebsocketConnect]);
        [$user] = $this->generateTestAccount([SubuserPermission::WebsocketConnect]);

        $this->actingAs($user)->getJson("/api/client/servers/$server->uuid/websocket")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * Test that the expected permissions are returned for the server owner and that the JWT is
     * configured correctly.
     */
    public function test_jwt_and_websocket_url_are_returned_for_server_owner(): void
    {
        /** @var \App\Models\User $user */
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount();

        // Force the node to HTTPS since we want to confirm it gets transformed to wss:// in the URL.
        $server->node->scheme = 'https';
        $server->node->save();

        $response = $this->actingAs($user)->getJson("/api/client/servers/$server->uuid/websocket");

        $response->assertOk();
        $response->assertJsonStructure(['data' => ['token', 'socket']]);

        $connection = $response->json('data.socket');
        $this->assertStringStartsWith('wss://', $connection);
        $this->assertStringEndsWith("/api/servers/$server->uuid/ws", $connection);

        $key = InMemory::plainText($server->node->daemon_token);
        $config = Configuration::forSymmetricSigner(new Sha256(), $key);

        $token = $config->parser()->parse($response->json('data.token'));
        $this->assertInstanceOf(UnencryptedToken::class, $token);

        $constraints = [new SignedWith(new Sha256(), $key)];
        $this->assertTrue(
            $config->validator()->validate($token, ...$constraints),
            'Failed to validate that the JWT data returned was signed using the Node\'s secret key.'
        );

        $expect = CarbonImmutable::createFromTimestamp(CarbonImmutable::now()->getTimestamp())->timezone('UTC')->setMicroseconds(0);

        $claims = $token->claims();
        $this->assertSame(config('app.url'), $claims->get('iss'));
        $this->assertSame($server->node->getConnectionAddress(), $claims->get('aud')[0] ?? null);
        $this->assertEquals($expect, CarbonImmutable::instance($claims->get('iat'))->setMicroseconds(0));
        $this->assertEquals($expect->subMinutes(5), CarbonImmutable::instance($claims->get('nbf'))->setMicroseconds(0));
        $this->assertEquals($expect->addMinutes(10), CarbonImmutable::instance($claims->get('exp'))->setMicroseconds(0));
        $this->assertSame($user->uuid, $claims->get('user_uuid'));
        $this->assertSame($server->uuid, $claims->get('server_uuid'));
        $this->assertSame(['*'], $claims->get('permissions'));
    }

    public function test_jwt_is_configured_correctly_for_server_subuser(): void
    {
        $permissions = [SubuserPermission::WebsocketConnect->value, SubuserPermission::ControlConsole->value];

        /** @var \App\Models\User $user */
        /** @var \App\Models\Server $server */
        [$user, $server] = $this->generateTestAccount($permissions);

        $response = $this->actingAs($user)->getJson("/api/client/servers/$server->uuid/websocket");

        $response->assertOk();
        $response->assertJsonStructure(['data' => ['token', 'socket']]);

        $key = InMemory::plainText($server->node->daemon_token);
        $config = Configuration::forSymmetricSigner(new Sha256(), $key);

        $token = $config->parser()->parse($response->json('data.token'));
        $this->assertInstanceOf(UnencryptedToken::class, $token);

        $constraints = [new SignedWith(new Sha256(), $key)];
        $this->assertTrue(
            $config->validator()->validate($token, ...$constraints),
            'Failed to validate that the JWT data returned was signed using the Node\'s secret key.'
        );

        $this->assertSame($permissions, $token->claims()->get('permissions'));
    }
}
