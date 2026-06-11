<?php

use App\Models\Node;

covers(Node::class);

it('emits the per-node daemon name when set', function () {
    $node = Node::factory()->create(['daemon_app_name' => 'Pelican Hosting']);

    expect($node->getConfiguration()['app_name'])->toBe('Pelican Hosting');
});

it('falls back to the panel name when the daemon name is blank', function () {
    $node = Node::factory()->create(['daemon_app_name' => null]);

    expect($node->getConfiguration()['app_name'])->toBe(config('app.name'));
});

it('tracks the panel name dynamically when not overridden', function () {
    $node = Node::factory()->create(['daemon_app_name' => null]);
    config(['app.name' => 'Renamed Panel']);

    expect($node->getConfiguration()['app_name'])->toBe('Renamed Panel');
});
