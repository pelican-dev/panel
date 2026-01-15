<?php

namespace App\Filament\Server\Pages;

use App\Models\Server;
use App\Traits\Filament\BlockAccessInConflict;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

/**
 * @property Schema $form
 */
abstract class ServerFormPage extends Page
{
    use BlockAccessInConflict;
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected string $view = 'filament.server.pages.server-form-page';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->fillForm();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->model($this->getRecord());
    }

    protected function authorizeAccess(): void {}

    protected function fillForm(): void
    {
        $data = $this->getRecord()->attributesToArray();

        $this->form->fill($data);
    }

    public function getRecord(): Server
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $server;
    }

    public function save(): void {}
}
