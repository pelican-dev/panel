<?php

namespace App\Filament\Server\Pages;

use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Components\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

/**
 * @property Form $form
 */
abstract class ServerFormPage extends Page
{
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected string $view = 'filament.server.pages.server-form-page';

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

    protected function fillform(): void
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

    // TODO: find better way handle server conflict state
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
    }
}
