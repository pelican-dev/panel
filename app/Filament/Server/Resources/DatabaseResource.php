<?php

namespace App\Filament\Server\Resources;

use App\Filament\Components\Actions\CopyAction;
use App\Filament\Components\Actions\RotateDatabasePasswordAction;
use App\Filament\Server\Resources\DatabaseResource\Pages\ListDatabases;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Models\Database;
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
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
                    ->suffixCopy(),
                TextInput::make('database')
                    ->label(trans('server/database.database'))
                    ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null),
                TextInput::make('username')
                    ->label(trans('server/database.username'))
                    ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null),
                TextInput::make('password')
                    ->label(trans('server/database.password'))
                    ->password()->revealable()
                    ->hidden(fn () => !auth()->user()->can(Permission::ACTION_DATABASE_VIEW_PASSWORD, $server))
                    ->hintAction(
                        RotateDatabasePasswordAction::make()
                            ->authorize(fn () => auth()->user()->can(Permission::ACTION_DATABASE_UPDATE, $server))
                    )
                    ->suffixCopy()
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
                    ->suffixCopy()
                    ->columnSpanFull()
                    ->formatStateUsing(fn (Database $database) => $database->jdbc),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function defaultTable(Table $table): Table
    {
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
                    ->modalHeading(fn (Database $database) => 'Viewing ' . $database->database),
                DeleteAction::make()
                    ->using(fn (Database $database, DatabaseManagementService $service) => $service->delete($database)),
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
