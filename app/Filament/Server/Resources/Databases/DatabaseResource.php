<?php

namespace App\Filament\Server\Resources\Databases;

use App\Filament\Components\Actions\RotateDatabasePasswordAction;
use App\Filament\Server\Resources\Databases\Pages\ListDatabases;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Databases\DatabaseManagementService;
use App\Traits\Filament\BlockAccessInConflict;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyForm;
use App\Traits\Filament\CanModifyTable;
use App\Traits\Filament\HasLimitBadge;
use Exception;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DatabaseResource extends Resource
{
    use BlockAccessInConflict;
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyForm;
    use CanModifyTable;
    use HasLimitBadge;

    protected static ?string $model = Database::class;

    protected static ?int $navigationSort = 6;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-database';

    protected static function getBadgeCount(): int
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $server->databases->count();
    }

    protected static function getBadgeLimit(): int
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $server->database_limit;
    }

    /**
     * @throws Exception
     */
    public static function defaultForm(Schema $schema): Schema
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $schema
            ->components([
                TextInput::make('host')
                    ->label(trans('server/database.host'))
                    ->formatStateUsing(fn (Database $database) => $database->address())
                    ->copyable(fn () => request()->isSecure()),
                TextInput::make('database')
                    ->label(trans('server/database.database'))
                    ->copyable(fn () => request()->isSecure()),
                TextInput::make('username')
                    ->label(trans('server/database.username'))
                    ->copyable(fn () => request()->isSecure()),
                TextInput::make('password')
                    ->label(trans('server/database.password'))
                    ->password()->revealable()
                    ->hidden(fn () => !auth()->user()->can(Permission::ACTION_DATABASE_VIEW_PASSWORD, $server))
                    ->hintAction(
                        RotateDatabasePasswordAction::make()
                            ->authorize(fn () => auth()->user()->can(Permission::ACTION_DATABASE_UPDATE, $server))
                    )
                    ->copyable(fn () => request()->isSecure())
                    ->formatStateUsing(fn (Database $database) => $database->password),
                TextInput::make('remote')
                    ->label(trans('server/database.remote')),
                TextInput::make('max_connections')
                    ->label(trans('server/database.max_connections'))
                    ->formatStateUsing(fn (Database $database) => $database->max_connections === 0 ? $database->max_connections : 'Unlimited'),
                TextInput::make('jdbc')
                    ->label(trans('server/database.jdbc'))
                    ->password()->revealable()
                    ->hidden(!auth()->user()->can(Permission::ACTION_DATABASE_VIEW_PASSWORD, $server))
                    ->copyable(fn () => request()->isSecure())
                    ->columnSpanFull()
                    ->formatStateUsing(fn (Database $database) => $database->jdbc),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function defaultTable(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $table
            ->columns([
                TextColumn::make('host')
                    ->label(trans('server/database.host'))
                    ->state(fn (Database $database) => $database->address())
                    ->badge(),
                TextColumn::make('database')
                    ->label(trans('server/database.database')),
                TextColumn::make('username')
                    ->label(trans('server/database.username')),
                TextColumn::make('remote')
                    ->label(trans('server/database.remote')),
                DateTimeColumn::make('created_at')
                    ->label(trans('server/database.created_at'))
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading(fn (Database $database) => trans('server/database.viewing', ['database' => $database->database])),
                DeleteAction::make()
                    ->using(function (Database $database, DatabaseManagementService $service) {
                        try {
                            $service->delete($database);

                            Notification::make()
                                ->title(trans('server/database.delete_notification', ['database' => $database->database]))
                                ->success()
                                ->send();
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title(trans('server/database.delete_notification_fail', ['database' => $database->database]))
                                ->danger()
                                ->send();

                            report($exception);
                        }
                    }),
            ])
            ->toolbarActions([
                CreateAction::make('new')
                    ->hiddenLabel()->iconButton()->iconSize(IconSize::ExtraLarge)
                    ->icon(fn () => $server->databases()->count() >= $server->database_limit ? 'tabler-database-x' : 'tabler-database-plus')
                    ->tooltip(fn () => $server->databases()->count() >= $server->database_limit ? trans('server/database.limit') : trans('server/database.create_database'))
                    ->disabled(fn () => $server->databases()->count() >= $server->database_limit)
                    ->color(fn () => $server->databases()->count() >= $server->database_limit ? 'danger' : 'primary')
                    ->createAnother(false)
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Select::make('database_host_id')
                                    ->label(trans('server/database.database_host'))
                                    ->columnSpan(2)
                                    ->required()
                                    ->placeholder(trans('server/database.database_host_select'))
                                    ->options(fn () => $server->node->databaseHosts->mapWithKeys(fn (DatabaseHost $databaseHost) => [$databaseHost->id => $databaseHost->name])),
                                TextInput::make('database')
                                    ->label(trans('server/database.name'))
                                    ->columnSpan(1)
                                    ->prefix('s'. $server->id . '_')
                                    ->hintIcon('tabler-question-mark', trans('server/database.name_hint')),
                                TextInput::make('remote')
                                    ->label(trans('server/database.connections_from'))
                                    ->columnSpan(1)
                                    ->default('%'),
                            ]),
                    ])
                    ->action(function ($data, DatabaseManagementService $service) use ($server) {
                        $data['database'] ??= Str::random(12);
                        $data['database'] = $service->generateUniqueDatabaseName($data['database'], $server->id);

                        try {
                            $service->create($server, $data);

                            Notification::make()
                                ->title(trans('server/database.create_notification', ['database' => $data['database']]))
                                ->success()
                                ->send();
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title(trans('server/database.create_notification_fail', ['database' => $data['database']]))
                                ->danger()
                                ->send();

                            report($exception);
                        }
                    }),
            ]);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_DATABASE_READ, Filament::getTenant());
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_DATABASE_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_DATABASE_CREATE, Filament::getTenant());
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_DATABASE_UPDATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_DATABASE_DELETE, Filament::getTenant());
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListDatabases::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/database.title');
    }
}
