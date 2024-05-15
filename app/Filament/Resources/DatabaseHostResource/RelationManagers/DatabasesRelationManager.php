<?php

namespace App\Filament\Resources\DatabaseHostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Webbingbrasil\FilamentCopyActions\Tables\CopyableTextColumn;

class DatabasesRelationManager extends RelationManager
{
    protected static string $relationship = 'databases';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('database')->columnSpanFull(),
                Forms\Components\TextInput::make('username'),
                Forms\Components\TextInput::make('password')->default('Soon™'),
                Forms\Components\TextInput::make('remote')->label('Connections From'),
                Forms\Components\TextInput::make('max_connections'),
                Forms\Components\TextInput::make('JDBC')->label('JDBC Connection String')->columnSpanFull()->default('Soon™'),
                Forms\Components\TextInput::make('created_at'),
                Forms\Components\TextInput::make('updated_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('servers')
            ->columns([
                Tables\Columns\TextColumn::make('database'),
                Tables\Columns\TextColumn::make('username'),
                //Tables\Columns\TextColumn::make('password'),
                Tables\Columns\TextColumn::make('remote'),
                Tables\Columns\TextColumn::make('server_id')
                    ->label('Belongs To'),
                // TODO ->url(route('filament.admin.resources.servers.edit', ['record', ''])),
                Tables\Columns\TextColumn::make('max_connections'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
            ]);
    }
}
