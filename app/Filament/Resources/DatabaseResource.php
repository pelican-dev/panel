<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DatabaseResource\Pages;
use App\Models\Database;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DatabaseResource extends Resource
{
    protected static ?string $model = Database::class;

    protected static ?string $navigationIcon = 'tabler-database';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('server_id')
                    ->label(trans_choice('strings.servers', 1))
                    ->relationship('server', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('database_host_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('database')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('remote')
                    ->required()
                    ->maxLength(191)
                    ->default('%'),
                Forms\Components\TextInput::make('username')
                    ->label(trans('strings.username'))
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('password')
                    ->label(trans('strings.password'))
                    ->password()
                    ->revealable()
                    ->required(),
                Forms\Components\TextInput::make('max_connections')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('server.name')
                    ->label(trans_choice('strings.servers', 1))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('database_host_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('database')
                    ->label(trans_choice('strings.databases', 1))
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->label(trans('strings.username'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('remote')
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_connections')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(trans('strings.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(trans('strings.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatabases::route('/'),
            'create' => Pages\CreateDatabase::route('/create'),
            'edit' => Pages\EditDatabase::route('/{record}/edit'),
        ];
    }
}
