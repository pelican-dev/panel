<?php

namespace App\Filament\Resources\WebhookResource\Pages;

use App\Models\WebhookConfiguration;
use App\Filament\Resources\WebhookResource;
use Filament\Actions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditWebhookConfiguration extends EditRecord
{
    protected static string $resource = WebhookResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('endpoint')
                    ->label('Endpoint')
                    ->activeUrl()
                    ->required(),
                TextInput::make('description')
                    ->label('Description')
                    ->required(),
                CheckboxList::make('events')
                    ->label('Events')
                    ->lazy()
                    ->options(fn () => WebhookConfiguration::filamentCheckboxList())
                    ->searchable()
                    ->bulkToggleable()
                    ->columns(3)
                    ->columnSpanFull()
                    ->gridDirection('row')
                    ->required(),
            ]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Delete')
                ->modalHeading('Are you sure you want to delete this?')
                ->modalDescription('')
                ->modalSubmitActionLabel('Delete'),
            $this->getSaveFormAction()->formId('form'),
        ];
    }
}
