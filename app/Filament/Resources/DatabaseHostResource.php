<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DatabaseHostResource\Pages;
use App\Filament\Resources\DatabaseHostResource\RelationManagers;
use App\Models\DatabaseHost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DatabaseHostResource extends Resource
{
    protected static ?string $model = DatabaseHost::class;

    protected static ?string $label = 'Databases';

    protected static ?string $navigationIcon = 'tabler-database';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('host')
                    ->helperText('The IP address or Domain name that should be used when attempting to connect to this MySQL host from this Panel to create new databases.')
                    ->required()
                    ->live()
                    ->debounce(500)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('name', $state))
                    ->maxLength(191),
                Forms\Components\TextInput::make('port')
                    ->helperText('The port that MySQL is running on for this host.')
                    ->required()
                    ->numeric()
                    ->default(3306)
                    ->minValue(0)
                    ->maxValue(65535),
                Forms\Components\TextInput::make('username')
                    ->helperText('The username of an account that has enough permissions to create new users and databases on the system.')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('password')
                    ->helperText('The password for the database user.')
                    ->password()
                    ->revealable()
                    ->maxLength(191)
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->helperText('A short identifier used to distinguish this location from others. Must be between 1 and 60 characters, for example, us.nyc.lvl3.')
                    ->required()
                    ->maxLength(60),
                Forms\Components\Select::make('node_id')
                    ->searchable()
                    ->preload()
                    ->helperText('This setting only defaults to this database host when adding a database to a server on the selected node.')
                    ->label('Linked Node')
                    ->relationship('node', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('host')
                    ->searchable(),
                Tables\Columns\TextColumn::make('port')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_databases')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListDatabaseHosts::route('/'),
            'create' => Pages\CreateDatabaseHost::route('/create'),
            'edit' => Pages\EditDatabaseHost::route('/{record}/edit'),
        ];
    }
}
