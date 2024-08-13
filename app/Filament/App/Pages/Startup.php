<?php

namespace App\Filament\App\Pages;

use App\Models\Permission;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Pages\Concerns\HasRelationManagers;

class Startup extends SimplePage
{
    use HasRelationManagers;

    protected static ?string $navigationIcon = 'tabler-player-play';

    protected static string $view = 'filament.app.pages.startup';

    public function form(Form $form): Form
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $form
            ->schema([
                Placeholder::make('')
                    ->content('Nothing to see here... yet!'),
                // TODO: display current startup command (read only)
                // TODO: select for docker image
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            // TODO: startup variables relation
        ];
    }

    protected function authorizeAccess(): void
    {
        abort_unless(!auth()->user()->can(Permission::ACTION_STARTUP_READ), 403);
    }
}
