<?php

use App\Enums\RolePermissionModels;
use App\Filament\Admin\Resources\Eggs\Pages\ListEggs;
use App\Models\Egg;
use App\Models\Role;

use function Pest\Livewire\livewire;

it('root admin can see all eggs', function () {
    $eggs = Egg::all();
    [$admin] = generateTestAccount([]);
    $admin = $admin->syncRoles(Role::getRootAdmin());

    $this->actingAs($admin);
    livewire(ListEggs::class)
        ->assertSuccessful()
        ->assertCountTableRecords($eggs->count())
        ->assertCanSeeTableRecords($eggs);
});

it('non root admin cannot see any eggs', function () {
    $role = Role::factory()->create(['name' => 'Node Viewer', 'guard_name' => 'web']);
    // Node Permission is on purpose, we check the wrong permissions.
    $permission = Permission::factory()->create(['name' => RolePermissionModels::Node->viewAny(), 'guard_name' => 'web']);
    $role->permissions()->attach($permission);
    [$user] = generateTestAccount([]);

    $this->actingAs($user);
    livewire(ListEggs::class)
        ->assertForbidden();
});

it('non root admin with permissions can see eggs', function () {
    $role = Role::factory()->create(['name' => 'Egg Viewer', 'guard_name' => 'web']);
    $permission = Permission::factory()->create(['name' => RolePermissionModels::Egg->viewAny(), 'guard_name' => 'web']);
    $role->permissions()->attach($permission);

    $eggs = Egg::all();
    [$user] = generateTestAccount([]);
    $user = $user->syncRoles($role);

    $this->actingAs($user);
    livewire(ListEggs::class)
        ->assertSuccessful()
        ->assertCountTableRecords($eggs->count())
        ->assertCanSeeTableRecords($eggs);
});
