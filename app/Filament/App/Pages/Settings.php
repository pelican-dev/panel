<?php

namespace App\Filament\App\Pages;

use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

/**
 * @property Form $form
 */
class Settings extends Page
{
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'tabler-settings';

    protected static string $view = 'filament.app.pages.settings';

    public ?array $data = [];

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

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->form->fill();
    }

    protected function authorizeAccess(): void
    {
        // TODO: check permissions
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form($this->makeForm()
                ->statePath($this->getFormStatePath())
                ->columns($this->hasInlineLabels() ? 1 : 2)
                ->inlineLabel($this->hasInlineLabels()),
            ),
        ];
    }

    public function getFormStatePath(): ?string
    {
        return 'data';
    }
}
