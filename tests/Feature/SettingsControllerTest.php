<?php

use App\Enums\ServerState;
use App\Http\Controllers\Api\Client\Servers\SettingsController;
use App\Models\Permission;
use App\Repositories\Daemon\DaemonServerRepository;
use Symfony\Component\HttpFoundation\Response;

pest()->group('API');

covers(SettingsController::class);

it('server name can be changed', function () {
    [$user, $server] = generateTestAccount([Permission::ACTION_WEBSOCKET_CONNECT]);
    $originalName = $server->name;

    $this->actingAs($user)
        ->post("/api/client/servers/$server->uuid/settings/rename", [
            'name' => 'Test Server Name',
        ])
        ->assertStatus(Response::HTTP_FORBIDDEN);

    $server = $server->refresh();
    $this->assertSame($originalName, $server->name);
});

//test('subuser cannot change server name without permission', function () {
//
//});

test('unauthorized user cannot change docker image in use by server', function () {
    [$user, $server] = generateTestAccount([Permission::ACTION_WEBSOCKET_CONNECT]);
    $originalImage = $server->image;

    $this->actingAs($user)
        ->put("/api/client/servers/$server->uuid/settings/docker-image", [
            'docker_image' => 'ghcr.io/pelican-dev/yolks:java_21',
        ])
        ->assertStatus(Response::HTTP_FORBIDDEN);

    $server = $server->refresh();
    $this->assertSame($originalImage, $server->image);
});

test('can change docker image in use by server', function () {
    [$user, $server] = generateTestAccount([Permission::ACTION_STARTUP_DOCKER_IMAGE]);
    $server->image = 'ghcr.io/parkervcp/yolks:java_17';
    $server->save();

    $newImage = 'ghcr.io/parkervcp/yolks:java_21';

    $this->actingAs($user)
        ->putJson("/api/client/servers/$server->uuid/settings/docker-image", [
            'docker_image' => $newImage,
        ])
        ->assertStatus(Response::HTTP_NO_CONTENT);

    $server = $server->refresh();

    expect($server->image)->toBe($newImage);
});

test('can be reinstalled', function () {
    [$user, $server] = generateTestAccount([Permission::ACTION_SETTINGS_REINSTALL]);
    expect($server->isInstalled())->toBeTrue();

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
    expect($server->status)->toBe(ServerState::Installing);
});
