<?php

namespace App\Filament\App\Pages;

use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;

class Settings extends SimplePage
{
    protected static ?string $navigationIcon = 'tabler-settings';

    protected static string $view = 'filament.app.pages.settings';

    public function form(Form $form): Form
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $form
            ->schema([
                Placeholder::make('')
                    ->content('Nothing to see here... yet!'),
                // TODO: sftp details (read only)
                // TODO: change server details (name & description)
                // TODO: debug info (node & server id)
                // TODO: reinstall server button
            ]);
    }

    protected function authorizeAccess(): void
    {
        // TODO: check permissions
    }
}
