<?php

use App\Filament\Admin\Resources\Servers\Pages\CreateServer;
use App\Filament\Admin\Resources\Servers\Pages\ListServers;
use App\Filament\Components\Actions\CloneServerAction;
use App\Models\Allocation;
use App\Models\Mount;
use App\Models\Role;
use App\Models\Server;
use App\Models\ServerVariable;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Ramsey\Uuid\Uuid;

use function Pest\Livewire\livewire;

beforeEach(function () {
    Filament::setCurrentPanel('admin');
});

it('pre-fills the create form with the configuration of the cloned server', function () {
    [$admin] = generateTestAccount([]);
    $admin = $admin->syncRoles(Role::getRootAdmin());

    $server = createServerModel([
        'name' => 'test-server',
        'memory' => 1024,
        'disk' => 2048,
        'cpu' => 150,
        'docker_labels' => ['foo' => 'bar'],
    ]);

    $variable = $server->egg->variables->firstWhere('env_variable', 'SERVER_JARFILE');
    ServerVariable::query()->create([
        'server_id' => $server->id,
        'variable_id' => $variable->id,
        'variable_value' => 'cloned.jar',
    ]);

    $this->actingAs($admin);
    Livewire::withQueryParams(['clone_from' => $server->id]);

    $component = livewire(CreateServer::class)
        ->assertSuccessful()
        ->assertNotified(trans('admin/server.notifications.clone_loaded', ['server' => 'test-server']))
        ->assertSchemaStateSet([
            'name' => 'test-server (Copy)',
            'external_id' => null,
            'node_id' => $server->node_id,
            'owner_id' => $server->owner_id,
            'allocation_id' => null,
            'egg_id' => $server->egg_id,
            'startup' => $server->startup,
            'image' => $server->image,
            'memory' => 1024,
            'disk' => 2048,
            'cpu' => 150,
            'docker_labels' => ['foo' => 'bar'],
        ]);

    expect($component->get('data.environment'))->toHaveKey($variable->env_variable, 'cloned.jar');
});

it('creates a server from the pre-filled clone form', function () {
    [$admin] = generateTestAccount([]);
    $admin = $admin->syncRoles(Role::getRootAdmin());

    $server = createServerModel(['name' => 'test-server']);
    $allocation = Allocation::factory()->create(['node_id' => $server->node_id]);

    $variable = $server->egg->variables->firstWhere('env_variable', 'SERVER_JARFILE');
    ServerVariable::query()->create([
        'server_id' => $server->id,
        'variable_id' => $variable->id,
        'variable_value' => 'cloned.jar',
    ]);

    $mount = Mount::query()->create([
        'uuid' => Uuid::uuid4()->toString(),
        'name' => 'test-mount',
        'source' => '/mnt/source',
        'target' => '/mnt/target',
        'read_only' => false,
        'user_mountable' => false,
    ]);
    $server->mounts()->attach($mount);

    Http::fake(['*' => Http::response([], 200)]);

    $this->actingAs($admin);
    Livewire::withQueryParams(['clone_from' => $server->id]);

    livewire(CreateServer::class)
        ->fillForm(['allocation_id' => $allocation->id])
        ->call('create')
        ->assertHasNoFormErrors();

    /** @var Server $clone */
    $clone = Server::query()->where('name', 'test-server (Copy)')->firstOrFail();

    expect($clone->egg_id)->toBe($server->egg_id)
        ->and($clone->node_id)->toBe($server->node_id)
        ->and($clone->owner_id)->toBe($server->owner_id)
        ->and($clone->memory)->toBe($server->memory)
        ->and($clone->disk)->toBe($server->disk)
        ->and($clone->startup)->toBe($server->startup)
        ->and($clone->image)->toBe($server->image)
        ->and($clone->allocation_id)->toBe($allocation->id)
        ->and($clone->external_id)->toBeNull()
        ->and($clone->variables->firstWhere('id', $variable->id)->server_value)->toBe('cloned.jar')
        ->and($clone->mounts->pluck('id')->all())->toBe([$mount->id]);
});

it('does not pre-fill the create form without a server to clone', function () {
    [$admin] = generateTestAccount([]);
    $admin = $admin->syncRoles(Role::getRootAdmin());

    createServerModel(['name' => 'test-server']);

    $this->actingAs($admin);

    livewire(CreateServer::class)
        ->assertSuccessful()
        ->assertSchemaStateSet(['name' => null]);
});

it('shows the clone action in the server list', function () {
    [$admin] = generateTestAccount([]);
    $admin = $admin->syncRoles(Role::getRootAdmin());

    $server = createServerModel();

    $this->actingAs($admin);

    livewire(ListServers::class)
        ->assertSuccessful()
        ->assertTableActionExists(CloneServerAction::class, record: $server);
});
