<?php

namespace App\Filament\App\Pages;

use App\Models\Permission;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Resources\Pages\Concerns\HasRelationManagers;

/**
 * @property Form $form
 */
class Startup extends Page
{
    use HasRelationManagers;
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'tabler-player-play';

    protected static string $view = 'filament.app.pages.startup';

    public ?array $data = [];

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

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->form->fill();
    }

    protected function authorizeAccess(): void
    {
        abort_unless(!auth()->user()->can(Permission::ACTION_STARTUP_READ), 403);
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
