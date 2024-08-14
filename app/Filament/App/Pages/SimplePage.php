<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

/**
 * @property Form $form
 */
abstract class SimplePage extends Page
{
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static string $view = 'filament.app.pages.simple-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->form->fill();
    }

    protected function authorizeAccess(): void
    {

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
