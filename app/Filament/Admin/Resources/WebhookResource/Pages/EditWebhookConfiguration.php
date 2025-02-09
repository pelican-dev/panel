<?php

namespace App\Filament\Admin\Resources\WebhookResource\Pages;

use App\Models\WebhookConfiguration;
use App\Filament\Admin\Resources\WebhookResource;
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
                    ->label(trans('admin/webhook.endpoint'))
                    ->activeUrl()
                    ->required(),
                TextInput::make('description')
                    ->label(trans('admin/webhook.description'))
                    ->required(),
                CheckboxList::make('events')
                    ->label(trans('admin/webhook.events'))
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
            Actions\DeleteAction::make(),
            $this->getSaveFormAction()->formId('form'),
        ];
    }
}
