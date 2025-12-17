<?php

use App\Enums\RolePermissionModels;
use App\Filament\Admin\Resources\Nodes\Pages\ListNodes;
use App\Models\Node;
use App\Models\Role;
use App\Models\Server;
use Filament\Actions\CreateAction;

use function Pest\Livewire\livewire;

it('root admin can see all nodes', function () {
    [$admin] = generateTestAccount([]);
    $admin = $admin->syncRoles(Role::getRootAdmin());
    $nodes = Node::all();

    $this->actingAs($admin);
    livewire(ListNodes::class)
        ->assertSuccessful()
        ->assertCountTableRecords($nodes->count())
        ->assertCanSeeTableRecords($nodes);
});

it('non root admin cannot see any nodes', function () {
    $role = Role::factory()->create(['name' => 'Egg Viewer', 'guard_name' => 'web']);
    // Egg Permission is on purpose, we check the wrong permissions.
    $permission = Permission::factory()->create(['name' => RolePermissionModels::Egg->viewAny(), 'guard_name' => 'web']);
    $role->permissions()->attach($permission);
    [$user] = generateTestAccount();

    $this->actingAs($user);
    livewire(ListNodes::class)
        ->assertForbidden();
});

it('non root admin with permissions can see nodes', function () {
    $role = Role::factory()->create(['name' => 'Node Viewer', 'guard_name' => 'web']);
    $permission = Permission::factory()->create(['name' => RolePermissionModels::Node->viewAny(), 'guard_name' => 'web']);
    $role->permissions()->attach($permission);

    [$user] = generateTestAccount();
    $nodes = Node::all();
    $user = $user->syncRoles($role);

    $this->actingAs($user);
    livewire(ListNodes::class)
        ->assertSuccessful()
        ->assertCountTableRecords($nodes->count())
        ->assertCanSeeTableRecords($nodes);
});

it('displays the create button in the table instead of the header when 0 nodes', function () {
    [$admin] = generateTestAccount([]);
    $admin = $admin->syncRoles(Role::getRootAdmin());

    // Nuke servers & nodes
    Server::truncate();
    Node::truncate();

    $this->actingAs($admin);
    livewire(ListNodes::class)
        ->assertSuccessful()
        ->assertHeaderMissing(CreateAction::class)
        ->assertActionExists(CreateAction::class);
});
