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
use Illuminate\Database\Eloquent\Builder;

class DatabaseHostResource extends Resource
{
    protected static ?string $model = DatabaseHost::class;

    protected static ?string $navigationIcon = 'tabler-database';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count() ?: null;
    }

    public static function getNavigationLabel(): string
    {
        return trans('admin/databasehost.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/databasehost.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/databasehost.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(trans('admin/databasehost.table.name')),
                TextColumn::make('host')
                    ->label(trans('admin/databasehost.table.host')),
                TextColumn::make('port')
                    ->label(trans('admin/databasehost.table.port')),
                TextColumn::make('username')
                    ->label(trans('admin/databasehost.table.username')),
                TextColumn::make('databases_count')
                    ->counts('databases')
                    ->icon('tabler-database')
                    ->label(trans('admin/databasehost.databases')),
                TextColumn::make('nodes.name')
                    ->icon('tabler-server-2')
                    ->badge()
                    ->placeholder(trans('admin/databasehost.no_nodes')),
            ])
            ->checkIfRecordIsSelectableUsing(fn (DatabaseHost $databaseHost) => !$databaseHost->databases_count)
            ->actions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->emptyStateIcon('tabler-database')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/databasehost.no_database_hosts'))
            ->emptyStateActions([
                CreateAction::make(),
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
                            ->label(trans('admin/databasehost.host'))
                            ->helperText(trans('admin/databasehost.host_help'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Set $set) => $set('name', $state))
                            ->maxLength(255),
                        TextInput::make('port')
                            ->columnSpan(1)
                            ->label(trans('admin/databasehost.port'))
                            ->helperText(trans('admin/databasehost.port_help'))
                            ->required()
                            ->numeric()
                            ->default(3306)
                            ->minValue(0)
                            ->maxValue(65535),
                        TextInput::make('max_databases')
                            ->label(trans('admin/databasehost.max_database'))
                            ->helpertext(trans('admin/databasehost.max_databases_help'))
                            ->numeric(),
                        TextInput::make('name')
                            ->label(trans('admin/databasehost.display_name'))
                            ->helperText(trans('admin/databasehost.display_name_help'))
                            ->required()
                            ->maxLength(60),
                        TextInput::make('username')
                            ->label(trans('admin/databasehost.username'))
                            ->helperText(trans('admin/databasehost.username_help'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label(trans('admin/databasehost.password'))
                            ->helperText(trans('admin/databasehost.password_help'))
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->required(fn ($operation) => $operation === 'create'),
                        Select::make('node_ids')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText(trans('admin/databasehost.linked_nodes_help'))
                            ->label(trans('admin/databasehost.linked_nodes'))
                            ->relationship('nodes', 'name', fn (Builder $query) => $query->whereIn('nodes.id', auth()->user()->accessibleNodes()->pluck('id'))),
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->where(function (Builder $query) {
            return $query->whereHas('nodes', function (Builder $query) {
                $query->whereIn('nodes.id', auth()->user()->accessibleNodes()->pluck('id'));
            })->orDoesntHave('nodes');
        });
    }
}
