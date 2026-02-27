<?php

use App\Events\ActivityLogged;
use App\Filament\Admin\Resources\Eggs\Pages\CreateEgg;
use App\Filament\Admin\Resources\Eggs\Pages\EditEgg;
use App\Filament\Admin\Resources\Nodes\Pages\CreateNode;
use App\Filament\Admin\Resources\Nodes\Pages\EditNode;
use App\Listeners\AdminActivityListener;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Role;
use Filament\Facades\Filament;
use Filament\Resources\Events\RecordCreated;
use Filament\Resources\Events\RecordUpdated;
use Illuminate\Support\Facades\Event;

function pageInstance(string $class): object
{
    return (new ReflectionClass($class))->newInstanceWithoutConstructor();
}

function createEvent(object $record, array $data, object $page): RecordCreated
{
    return new RecordCreated($record, $data, $page);
}

function updateEvent(object $record, array $data, object $page): RecordUpdated
{
    return new RecordUpdated($record, $data, $page);
}

beforeEach(function () {
    [$this->admin] = generateTestAccount([]);
    $this->admin = $this->admin->syncRoles(Role::getRootAdmin());
    $this->actingAs($this->admin);

    Filament::setCurrentPanel('admin');
});

it('logs create activity for an egg', function () {
    $egg = Egg::first();

    $listener = new AdminActivityListener();
    $listener->handle(createEvent($egg, ['name' => 'Test Egg'], pageInstance(CreateEgg::class)));

    $this->assertActivityLogged('admin:egg.create');
});

it('logs update activity for an egg', function () {
    $egg = Egg::first();

    $listener = new AdminActivityListener();
    $listener->handle(updateEvent($egg, ['name' => 'Updated Egg'], pageInstance(EditEgg::class)));

    $this->assertActivityLogged('admin:egg.update');
});

it('logs create activity for a node', function () {
    $node = Node::first();

    $listener = new AdminActivityListener();
    $listener->handle(createEvent($node, ['name' => 'Test Node'], pageInstance(CreateNode::class)));

    $this->assertActivityLogged('admin:node.create');
});

it('logs update activity for a node', function () {
    $node = Node::first();

    $listener = new AdminActivityListener();
    $listener->handle(updateEvent($node, ['name' => 'Updated Node'], pageInstance(EditNode::class)));

    $this->assertActivityLogged('admin:node.update');
});

it('does not log activity for non-admin panels', function () {
    Filament::setCurrentPanel('app');

    $egg = Egg::first();

    $listener = new AdminActivityListener();
    $listener->handle(createEvent($egg, ['name' => 'Test'], pageInstance(CreateEgg::class)));

    Event::assertNotDispatched(ActivityLogged::class);
});

it('sets the record as the activity subject', function () {
    $egg = Egg::first();

    $listener = new AdminActivityListener();
    $listener->handle(createEvent($egg, ['name' => 'Test'], pageInstance(CreateEgg::class)));

    $this->assertActivityFor('admin:egg.create', $this->admin, $egg);
});

it('redacts sensitive fields from activity properties', function () {
    $egg = Egg::first();

    $data = [
        'name' => 'Visible',
        'password' => 'should-be-redacted',
        'password_confirmation' => 'should-be-redacted',
        'token' => 'should-be-redacted',
        'secret' => 'should-be-redacted',
        'api_key' => 'should-be-redacted',
    ];

    $listener = new AdminActivityListener();
    $listener->handle(updateEvent($egg, $data, pageInstance(EditEgg::class)));

    Event::assertDispatched(ActivityLogged::class, function (ActivityLogged $event) {
        $properties = $event->model->properties;

        expect($properties)->toHaveKey('name', 'Visible')
            ->toHaveKey('password', '[REDACTED]')
            ->toHaveKey('password_confirmation', '[REDACTED]')
            ->toHaveKey('token', '[REDACTED]')
            ->toHaveKey('secret', '[REDACTED]')
            ->toHaveKey('api_key', '[REDACTED]');

        return true;
    });
});

it('redacts sensitive fields in nested arrays', function () {
    $egg = Egg::first();

    $data = [
        'name' => 'Visible',
        'nested' => [
            'safe' => 'value',
            'password' => 'should-be-redacted',
            'token' => 'should-be-redacted',
        ],
    ];

    $listener = new AdminActivityListener();
    $listener->handle(updateEvent($egg, $data, pageInstance(EditEgg::class)));

    Event::assertDispatched(ActivityLogged::class, function (ActivityLogged $event) {
        $properties = $event->model->properties;

        expect($properties['nested'])->toHaveKey('safe', 'value')
            ->toHaveKey('password', '[REDACTED]')
            ->toHaveKey('token', '[REDACTED]');

        return true;
    });
});

it('generates kebab-case event names from model class names', function () {
    $node = Node::first();

    $listener = new AdminActivityListener();
    $listener->handle(createEvent($node, ['name' => 'Test'], pageInstance(CreateNode::class)));

    $this->assertActivityLogged('admin:node.create');
});
