<?php

namespace App\Filament\Resources\DatabaseResource\Pages;

use App\Filament\Resources\DatabaseResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditDatabase extends EditRecord
{
    protected static string $resource = DatabaseResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('server_id')
                    ->relationship('server', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('database_host_id')
                    ->required()
                    ->numeric(),
                TextInput::make('database')
                    ->required()
                    ->maxLength(255),
                TextInput::make('remote')
                    ->required()
                    ->maxLength(255)
                    ->default('%'),
                TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(),
                TextInput::make('max_connections')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
