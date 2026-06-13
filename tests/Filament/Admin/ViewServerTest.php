<?php

use App\Enums\RolePermissionModels;
use App\Filament\Admin\Resources\Servers\Pages\EditServer;
use App\Filament\Admin\Resources\Servers\Pages\ListServers;
use App\Filament\Admin\Resources\Servers\Pages\ViewServer;
use App\Filament\Admin\Resources\Servers\RelationManagers\AllocationsRelationManager;
use App\Filament\Admin\Resources\Servers\RelationManagers\DatabasesRelationManager;
use App\Models\Allocation;
use App\Models\Role;
use App\Models\Server;
use App\Models\ServerVariable;
use Filament\Actions\AssociateAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

use function Pest\Livewire\livewire;

/** @param string[] $abilities */
function serverRole(string $name, array $abilities): Role
{
    $role = Role::factory()->create(['name' => $name, 'guard_name' => 'web']);

    foreach ($abilities as $ability) {
        $role->givePermissionTo(Permission::findOrCreate($ability, 'web'));
    }

    return $role;
}

function serverAllocation(Server $server): Allocation
{
    return Allocation::factory()->create([
        'node_id' => $server->node->getKey(),
        'server_id' => $server->getKey(),
    ]);
}

function assertServerViewIsReadOnly(Server $server, Allocation $allocation): void
{
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $primaryBefore = $server->allocation_id;
    $aliasBefore = $allocation->ip_alias;
    $notesBefore = $allocation->notes;

    livewire(AllocationsRelationManager::class, [
        'ownerRecord' => $server,
        'pageClass' => ViewServer::class,
    ])
        ->assertActionHidden(TestAction::make('make-primary')->table($allocation))
        ->assertActionHidden(TestAction::make('lock')->table($allocation))
        ->assertActionHidden(TestAction::make(DissociateAction::class)->table($allocation))
        ->assertActionHidden(TestAction::make(CreateAction::class)->table())
        ->assertActionHidden(TestAction::make(AssociateAction::class)->table())
        ->assertActionHidden(TestAction::make(DissociateBulkAction::class)->table()->bulk())
        // primary is an IconColumn action, not hideable; callTableColumnAction ignores disabled(), so the
        // in-closure isReadOnly guard is what must stop the write, and the unchanged allocation_id proves it
        ->callTableColumnAction('primary', $allocation->getKey())
        ->call('updateTableColumnState', 'ip_alias', (string) $allocation->getKey(), 'hacked-alias')
        ->call('updateTableColumnState', 'notes', (string) $allocation->getKey(), 'hacked-notes');

    livewire(DatabasesRelationManager::class, [
        'ownerRecord' => $server,
        'pageClass' => ViewServer::class,
    ])
        ->assertActionHidden(TestAction::make(CreateAction::class)->table())
        ->assertActionHidden(TestAction::make(DeleteAction::class)->table());

    expect($server->refresh()->allocation_id)->toBe($primaryBefore)
        ->and($allocation->refresh()->ip_alias)->toBe($aliasBefore)
        ->and($allocation->notes)->toBe($notesBefore);
}

// the rotate hintAction sits inside the database view modal, which standalone relation managers can't mount in
// tests, and isHidden() folds in the action's record-scoped authorize(); so resolve the action off the built
// form and evaluate just its view-page gate (the ->hidden closure wired to the manager isReadOnly state)
function databaseRotateHidden(Server $server, string $pageClass): bool
{
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $manager = livewire(DatabasesRelationManager::class, [
        'ownerRecord' => $server,
        'pageClass' => $pageClass,
    ])->instance();

    $password = collect($manager->form(Schema::make($manager))->getComponents())
        ->first(fn ($component) => $component instanceof TextInput && $component->getName() === 'password');

    $rotate = collect($password->getHintActions())
        ->first(fn ($action) => $action->getName() === 'exclude_hint_rotate');

    $gate = (new ReflectionProperty($rotate, 'isHidden'))->getValue($rotate);

    return (bool) $rotate->evaluate($gate);
}

it('lets a user with view permission open the view page', function () {
    [$user, $server] = generateTestAccount([]);
    $user->syncRoles(serverRole('Server Viewer', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
    ]));

    $this->actingAs($user);
    livewire(ViewServer::class, ['record' => $server->getKey()])
        ->assertSuccessful();
});

it('forbids the view page without view permission', function () {
    [$user, $server] = generateTestAccount([]);
    $user->syncRoles(serverRole('Egg Viewer', [
        RolePermissionModels::Egg->view(),
    ]));

    $this->actingAs($user);
    livewire(ViewServer::class, ['record' => $server->getKey()])
        ->assertForbidden();
});

it('forbids the edit page for a view-only user', function () {
    [$user, $server] = generateTestAccount([]);
    $user->syncRoles(serverRole('Server Viewer', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
    ]));

    $this->actingAs($user);
    livewire(EditServer::class, ['record' => $server->getKey()])
        ->assertForbidden();
});

it('does not materialize server variables when the view page is mounted', function () {
    [$user, $server] = generateTestAccount([]);
    $user->syncRoles(serverRole('Server Viewer', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
    ]));

    ServerVariable::query()->where('server_id', $server->getKey())->delete();
    $before = ServerVariable::query()->where('server_id', $server->getKey())->count();

    $this->actingAs($user);
    livewire(ViewServer::class, ['record' => $server->getKey()])
        ->assertSuccessful();

    expect(ServerVariable::query()->where('server_id', $server->getKey())->count())->toBe($before);
});

it('hides the reinstall action on the view page but keeps it on edit', function () {
    [$editor, $server] = generateTestAccount([]);
    $editor->syncRoles(serverRole('Server Editor', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
        RolePermissionModels::Server->update(),
    ]));

    [$viewer] = generateTestAccount([]);
    $viewer->syncRoles(serverRole('Server Viewer', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
    ]));

    $this->actingAs($editor);
    livewire(EditServer::class, ['record' => $server->getKey()])
        ->assertActionVisible(TestAction::make('exclude_reinstall')->schemaComponent(true));

    $this->actingAs($viewer);
    livewire(ViewServer::class, ['record' => $server->getKey()])
        ->assertActionDoesNotExist(TestAction::make('exclude_reinstall')->schemaComponent(true));
});

it('shows the view row action only when the user cannot edit', function () {
    [$viewer, $server] = generateTestAccount([]);
    $viewer->syncRoles(serverRole('Server Viewer', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
    ]));

    [$editor] = generateTestAccount([]);
    $editor->syncRoles(serverRole('Server Editor', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
        RolePermissionModels::Server->update(),
    ]));

    // table action urls resolve against the current panel; the default is 'app', not 'admin'
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $this->actingAs($viewer);
    livewire(ListServers::class)
        ->assertActionVisible(TestAction::make('view')->table($server));

    $this->actingAs($editor);
    livewire(ListServers::class)
        ->assertActionHidden(TestAction::make('view')->table($server));
});

it('keeps the server relation managers read-only on the view page for a view-only user', function () {
    [$viewer, $server] = generateTestAccount([]);
    $viewer->syncRoles(serverRole('Server Viewer', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
    ]));

    $this->actingAs($viewer);
    assertServerViewIsReadOnly($server, serverAllocation($server));
});

it('keeps the server relation managers read-only on the view page even for an update-capable user', function () {
    [$editor, $server] = generateTestAccount([]);
    $editor->syncRoles(serverRole('Server Editor', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
        RolePermissionModels::Server->update(),
    ]));

    $this->actingAs($editor);
    assertServerViewIsReadOnly($server, serverAllocation($server));
});

it('hides database password rotation behind the read-only gate on the view page', function () {
    [$viewer, $server] = generateTestAccount([]);
    $viewer->syncRoles(serverRole('Server Viewer', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
    ]));

    $this->actingAs($viewer);
    expect(databaseRotateHidden($server, ViewServer::class))->toBeTrue();
});

it('leaves database password rotation available on the edit page', function () {
    [$editor, $server] = generateTestAccount([]);
    $editor->syncRoles(serverRole('Server Editor', [
        RolePermissionModels::Server->viewAny(),
        RolePermissionModels::Server->view(),
        RolePermissionModels::Server->update(),
    ]));

    $this->actingAs($editor);
    expect(databaseRotateHidden($server, EditServer::class))->toBeFalse();
});
