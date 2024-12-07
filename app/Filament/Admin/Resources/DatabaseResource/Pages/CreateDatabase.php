<?php

namespace App\Filament\Admin\Resources\DatabaseResource\Pages;

use App\Filament\Admin\Resources\DatabaseResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateDatabase extends CreateRecord
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
                Select::make('database_host_id')
                    ->relationship('host', 'name')
                    ->searchable()
                    ->selectablePlaceholder(false)
                    ->preload()
                    ->required(),
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
}
