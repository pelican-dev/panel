<?php

namespace App\Filament\Server\Pages;

use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

/**
 * @property Form $form
 */
abstract class ServerFormPage extends Page
{
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static string $view = 'filament.server.pages.server-form-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->fillForm();
    }

    protected function authorizeAccess(): void {}

    protected function fillForm(): void
    {
        $data = $this->getRecord()->attributesToArray();
        $this->form->fill($data);
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form($this->makeForm()
                ->model($this->getRecord())
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
