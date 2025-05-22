<?php

namespace App\Filament\Server\Resources;

use App\Filament\Components\Forms\Actions\RotateDatabasePasswordAction;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Filament\Server\Resources\DatabaseResource\Pages;
use App\Models\Database;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Databases\DatabaseManagementService;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class DatabaseResource extends Resource
{
    protected static ?string $model = Database::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'tabler-database';

    public const WARNING_THRESHOLD = 0.7;

    public static function getNavigationBadge(): string
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $limit = $server->database_limit;

        return $server->databases->count() . ($limit === 0 ? '' : ' / ' . $limit);
    }

    public static function getNavigationBadgeColor(): ?string
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $limit = $server->database_limit;
        $count = $server->databases->count();

        if ($limit === 0) {
            return null;
        }

        return $count >= $limit ? 'danger' : ($count >= $limit * self::WARNING_THRESHOLD ? 'warning' : 'success');
    }

    public static function form(Form $form): Form
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $form
            ->schema([
                TextInput::make('host')
                    ->formatStateUsing(fn (Database $database) => $database->address())
                    ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null),
                TextInput::make('database')
                    ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null),
                TextInput::make('username')
                    ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null),
                TextInput::make('password')
                    ->password()->revealable()
                    ->hidden(fn () => !auth()->user()->can(Permission::ACTION_DATABASE_VIEW_PASSWORD, $server))
                    ->hintAction(
                        RotateDatabasePasswordAction::make()
                            ->authorize(fn () => auth()->user()->can(Permission::ACTION_DATABASE_UPDATE, $server))
                    )
                    ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                    ->formatStateUsing(fn (Database $database) => $database->password),
                TextInput::make('remote')
                    ->label('Connections From'),
                TextInput::make('max_connections')
                    ->formatStateUsing(fn (Database $database) => $database->max_connections === 0 ? $database->max_connections : 'Unlimited'),
                TextInput::make('jdbc')
                    ->label('JDBC Connection String')
                    ->password()->revealable()
                    ->hidden(!auth()->user()->can(Permission::ACTION_DATABASE_VIEW_PASSWORD, $server))
                    ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                    ->columnSpanFull()
                    ->formatStateUsing(fn (Database $database) => $database->jdbc),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('host')
                    ->state(fn (Database $database) => $database->address())
                    ->badge(),
                TextColumn::make('database'),
                TextColumn::make('username'),
                TextColumn::make('remote'),
                DateTimeColumn::make('created_at')
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make()
                    ->modalHeading(fn (Database $database) => 'Viewing ' . $database->database),
                DeleteAction::make()
                    ->using(fn (Database $database, DatabaseManagementService $service) => $service->delete($database)),
            ]);
    }

    // TODO: find better way handle server conflict state
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatabases::route('/'),
        ];
    }
}
