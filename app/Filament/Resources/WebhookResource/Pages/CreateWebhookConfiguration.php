<?php

namespace App\Filament\Resources\WebhookResource\Pages;

use App\Filament\Resources\WebhookResource;
use App\Models\WebhookConfiguration;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateWebhookConfiguration extends CreateRecord
{
    protected static string $resource = WebhookResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('endpoint')
                    ->activeUrl()
                    ->required(),
                TextInput::make('description')
                    ->required(),
                CheckboxList::make('events')
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
}
