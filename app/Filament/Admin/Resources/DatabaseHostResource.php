<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DatabaseHostResource\Pages;
use App\Models\DatabaseHost;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DatabaseHostResource extends Resource
{
    protected static ?string $model = DatabaseHost::class;

    protected static ?string $navigationIcon = 'tabler-database';

    protected static ?string $navigationGroup = 'Advanced';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('host'),
                TextColumn::make('port'),
                TextColumn::make('username'),
                TextColumn::make('databases_count')
                    ->counts('databases')
                    ->icon('tabler-database')
                    ->label('Databases'),
                TextColumn::make('nodes.name')
                    ->icon('tabler-server-2')
                    ->badge()
                    ->placeholder('No Nodes'),
            ])
            ->checkIfRecordIsSelectableUsing(fn (DatabaseHost $databaseHost) => !$databaseHost->databases_count)
            ->actions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->authorize(fn () => auth()->user()->can('delete databasehost')),
            ])
            ->emptyStateIcon('tabler-database')
            ->emptyStateDescription('')
            ->emptyStateHeading('No Database Hosts')
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Create Database Host')
                    ->button(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'default' => 2,
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 4,
                    ])
                    ->schema([
                        TextInput::make('host')
                            ->columnSpan(2)
                            ->helperText('The IP address or Domain name that should be used when attempting to connect to this MySQL host from this Panel to create new databases.')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Set $set) => $set('name', $state))
                            ->maxLength(255),
                        TextInput::make('port')
                            ->columnSpan(1)
                            ->helperText('The port that MySQL is running on for this host.')
                            ->required()
                            ->numeric()
                            ->default(3306)
                            ->minValue(0)
                            ->maxValue(65535),
                        TextInput::make('max_databases')
                            ->label('Max databases')
                            ->helpertext('Blank is unlimited.')
                            ->numeric(),
                        TextInput::make('name')
                            ->label('Display Name')
                            ->helperText('A short identifier used to distinguish this location from others. Must be between 1 and 60 characters, for example, us.nyc.lvl3.')
                            ->required()
                            ->maxLength(60),
                        TextInput::make('username')
                            ->helperText('The username of an account that has enough permissions to create new users and databases on the system.')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->helperText('The password for the database user.')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->required(fn ($operation) => $operation === 'create'),
                        Select::make('node_ids')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('This setting only defaults to this database host when adding a database to a server on the selected node.')
                            ->label('Linked Nodes')
                            ->relationship('nodes', 'name'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatabaseHosts::route('/'),
            'create' => Pages\CreateDatabaseHost::route('/create'),
            'view' => Pages\ViewDatabaseHost::route('/{record}'),
            'edit' => Pages\EditDatabaseHost::route('/{record}/edit'),
        ];
    }
}
