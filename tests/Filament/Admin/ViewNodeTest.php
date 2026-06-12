<?php

use App\Enums\RolePermissionModels;
use App\Filament\Admin\Resources\Nodes\Pages\EditNode;
use App\Filament\Admin\Resources\Nodes\Pages\ListNodes;
use App\Filament\Admin\Resources\Nodes\Pages\ViewNode;
use App\Filament\Admin\Resources\Nodes\RelationManagers\AllocationsRelationManager;
use App\Filament\Admin\Resources\Nodes\RelationManagers\ServersRelationManager;
use App\Filament\Components\Actions\UpdateNodeAllocations;
use App\Models\Allocation;
use App\Models\Node;
use App\Models\Role;
use App\Models\Server;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Permission;

use function Pest\Livewire\livewire;

/** @param string[] $abilities */
function nodeRole(string $name, array $abilities): Role
{
    $role = Role::factory()->create(['name' => $name, 'guard_name' => 'web']);

    foreach ($abilities as $ability) {
        $role->givePermissionTo(Permission::findOrCreate($ability, 'web'));
    }

    return $role;
}

/** @return array{0: Node, 1: Allocation, 2: Server} */
function nodeWithMultiAllocationServer(): array
{
    $node = Node::factory()->create();
    $server = Server::factory()->withNode($node)->create();
    $allocations = Allocation::factory()->count(2)->create([
        'node_id' => $node->getKey(),
        'server_id' => $server->getKey(),
    ]);
    $server->update(['allocation_id' => $allocations->first()->getKey()]);

    return [$node, $allocations->first(), $server->refresh()];
}

function assertNodeViewIsReadOnly(Node $node, Allocation $allocation, Server $server): void
{
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $alias = $allocation->ip_alias;
    $notes = $allocation->notes;
    $primary = $server->allocation_id;
    $otherAllocationId = $server->allocations->last()->getKey();

    livewire(AllocationsRelationManager::class, [
        'ownerRecord' => $node,
        'pageClass' => ViewNode::class,
    ])
        ->assertTableActionHidden('create new allocation')
        ->assertTableActionHidden(UpdateNodeAllocations::class)
        ->assertTableBulkActionHidden(DeleteBulkAction::class)
        ->call('updateTableColumnState', 'ip_alias', (string) $allocation->getKey(), 'hacked-alias')
        ->call('updateTableColumnState', 'notes', (string) $allocation->getKey(), 'hacked-notes');

    livewire(ServersRelationManager::class, [
        'ownerRecord' => $node,
        'pageClass' => ViewNode::class,
    ])
        ->call('updateTableColumnState', 'allocation.id', (string) $server->getKey(), (string) $otherAllocationId);

    expect($allocation->refresh()->ip_alias)->toBe($alias)
        ->and($allocation->notes)->toBe($notes)
        ->and($server->refresh()->allocation_id)->toBe($primary);
}

it('lets a user with view permission open the view page', function () {
    $node = Node::factory()->create();

    [$user] = generateTestAccount([]);
    $user->syncRoles(nodeRole('Node Viewer', [
        RolePermissionModels::Node->viewAny(),
        RolePermissionModels::Node->view(),
    ]));

    $this->actingAs($user);
    livewire(ViewNode::class, ['record' => $node->getKey()])
        ->assertSuccessful();
});

it('forbids the view page without view permission', function () {
    $node = Node::factory()->create();

    [$user] = generateTestAccount([]);
    $user->syncRoles(nodeRole('Egg Viewer', [
        RolePermissionModels::Egg->view(),
    ]));

    $this->actingAs($user);
    livewire(ViewNode::class, ['record' => $node->getKey()])
        ->assertForbidden();
});

it('forbids the edit page for a view-only user', function () {
    $node = Node::factory()->create();

    [$user] = generateTestAccount([]);
    $user->syncRoles(nodeRole('Node Viewer', [
        RolePermissionModels::Node->viewAny(),
        RolePermissionModels::Node->view(),
    ]));

    $this->actingAs($user);
    livewire(EditNode::class, ['record' => $node->getKey()])
        ->assertForbidden();
});

it('hides the reset-token action on the view page but keeps it on edit', function () {
    $node = Node::factory()->create();

    [$editor] = generateTestAccount([]);
    $editor->syncRoles(nodeRole('Node Editor', [
        RolePermissionModels::Node->viewAny(),
        RolePermissionModels::Node->view(),
        RolePermissionModels::Node->update(),
    ]));

    [$viewer] = generateTestAccount([]);
    $viewer->syncRoles(nodeRole('Node Viewer', [
        RolePermissionModels::Node->viewAny(),
        RolePermissionModels::Node->view(),
    ]));

    $this->actingAs($editor);
    livewire(EditNode::class, ['record' => $node->getKey()])
        ->assertActionVisible(TestAction::make('exclude_resetKey')->schemaComponent(true));

    $this->actingAs($viewer);
    livewire(ViewNode::class, ['record' => $node->getKey()])
        ->assertActionDoesNotExist(TestAction::make('exclude_resetKey')->schemaComponent(true));
});

it('shows the view row action only when the user cannot edit', function () {
    $node = Node::factory()->create();

    [$viewer] = generateTestAccount([]);
    $viewer->syncRoles(nodeRole('Node Viewer', [
        RolePermissionModels::Node->viewAny(),
        RolePermissionModels::Node->view(),
    ]));

    [$editor] = generateTestAccount([]);
    $editor->syncRoles(nodeRole('Node Editor', [
        RolePermissionModels::Node->viewAny(),
        RolePermissionModels::Node->view(),
        RolePermissionModels::Node->update(),
    ]));

    // table action urls resolve against the current panel; the default is 'app', not 'admin'
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $this->actingAs($viewer);
    livewire(ListNodes::class)
        ->assertTableActionVisible('view', $node);

    $this->actingAs($editor);
    livewire(ListNodes::class)
        ->assertTableActionHidden('view', $node);
});

it('keeps the node relation managers read-only on the view page for a view-only user', function () {
    [$node, $allocation, $server] = nodeWithMultiAllocationServer();

    [$viewer] = generateTestAccount([]);
    $viewer->syncRoles(nodeRole('Node Viewer', [
        RolePermissionModels::Node->viewAny(),
        RolePermissionModels::Node->view(),
    ]));

    $this->actingAs($viewer);
    assertNodeViewIsReadOnly($node, $allocation, $server);
});

it('keeps the node relation managers read-only on the view page even for an update-capable user', function () {
    [$node, $allocation, $server] = nodeWithMultiAllocationServer();

    [$editor] = generateTestAccount([]);
    $editor->syncRoles(nodeRole('Node Editor', [
        RolePermissionModels::Node->viewAny(),
        RolePermissionModels::Node->view(),
        RolePermissionModels::Node->update(),
    ]));

    $this->actingAs($editor);
    assertNodeViewIsReadOnly($node, $allocation, $server);
});
