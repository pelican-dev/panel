<?php

namespace App\Filament\Resources\DatabaseResource\Pages;

use App\Filament\Resources\DatabaseResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;

class EditDatabase extends EditRecord
{
    protected static string $resource = DatabaseResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('server_id')
                    ->relationship('server', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('database_host_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('database')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('remote')
                    ->required()
                    ->maxLength(255)
                    ->default('%'),
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(),
                Forms\Components\TextInput::make('max_connections')
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
