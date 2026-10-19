<?php

use App\Filament\Admin\Resources\Eggs\Pages\ListEggs;
use App\Jobs\InstallEgg;
use App\Models\Role;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Queue;

use function Pest\Livewire\livewire;

beforeEach(fn () => Filament::setCurrentPanel(Filament::getPanel('admin')));
afterEach(fn () => Filament::setCurrentPanel(null));

it('does not start a github install when no github eggs are selected', function () {
    Queue::fake();
    cache()->put('eggs.index', ['Minecraft' => ['https://example.com/egg-paper.json' => 'Paper']]);

    [$admin] = generateTestAccount([]);
    $this->actingAs($admin->syncRoles(Role::getRootAdmin()));

    livewire(ListEggs::class)
        ->callAction(TestAction::make('import')->table(), ['urls' => [['url' => '']]])
        ->assertHasNoFormErrors()
        ->assertNotNotified(trans('installer.egg.background_install_started'));

    Queue::assertNotPushed(InstallEgg::class);
});
