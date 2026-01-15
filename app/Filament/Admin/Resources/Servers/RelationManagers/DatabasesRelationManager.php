<?php

namespace App\Filament\Admin\Resources\Servers\RelationManagers;

use App\Filament\Components\Actions\RotateDatabasePasswordAction;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Models\Server;
use App\Services\Databases\DatabaseManagementService;
use App\Services\Servers\RandomWordService;
use Exception;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * @method Server getOwnerRecord()
 */
class DatabasesRelationManager extends RelationManager
{
    protected static string $relationship = 'databases';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('database')
                    ->columnSpanFull(),
                TextInput::make('username')
                    ->label(trans('admin/databasehost.table.username')),
                TextInput::make('password')
                    ->label(trans('admin/databasehost.table.password'))
                    ->password()
                    ->revealable()
                    ->hintAction(RotateDatabasePasswordAction::make())
                    ->formatStateUsing(fn (Database $database) => $database->password),
                TextInput::make('remote')
                    ->label(trans('admin/databasehost.table.remote'))
                    ->formatStateUsing(fn (Database $record) => $record->remote === '%' ? trans('admin/databasehost.anywhere'). ' ( % )' : $record->remote),
                TextInput::make('max_connections')
                    ->label(trans('admin/databasehost.table.max_connections'))
                    ->formatStateUsing(fn (Database $record) => $record->max_connections ?: trans('admin/databasehost.unlimited')),
                TextInput::make('jdbc')
                    ->label(trans('admin/databasehost.table.connection_string'))
                    ->columnSpanFull()
                    ->password()
                    ->revealable()
                    ->formatStateUsing(fn (Database $database) => $database->jdbc),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('')
            ->recordTitleAttribute('database')
            ->columns([
                TextColumn::make('database'),
                TextColumn::make('username')
                    ->label(trans('admin/databasehost.table.username')),
                TextColumn::make('remote')
                    ->label(trans('admin/databasehost.table.remote'))
                    ->formatStateUsing(fn (Database $record) => $record->remote === '%' ? trans('admin/databasehost.anywhere'). ' ( % )' : $record->remote),
                TextColumn::make('server.name')
                    ->url(fn (Database $database) => route('filament.admin.resources.servers.edit', ['record' => $database->server_id])),
                TextColumn::make('max_connections')
                    ->label(trans('admin/databasehost.table.max_connections'))
                    ->formatStateUsing(fn ($record) => $record->max_connections ?: trans('admin/databasehost.unlimited')),
                DateTimeColumn::make('created_at')
                    ->label(trans('admin/databasehost.table.created_at')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->color('primary'),
                DeleteAction::make()
                    ->iconButton()->iconSize(IconSize::ExtraLarge)
                    ->successNotificationTitle(null)
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
                CreateAction::make()
                    ->disabled(fn () => DatabaseHost::count() < 1)
                    ->label(fn () => DatabaseHost::count() < 1 ? trans('admin/server.no_db_hosts') : trans('admin/server.create_database'))
                    ->color(fn () => DatabaseHost::count() < 1 ? 'danger' : 'primary')
                    ->icon(fn () => DatabaseHost::count() < 1 ? 'tabler-database-x' : 'tabler-database-plus')
                    ->iconButton()->iconSize(IconSize::ExtraLarge)
                    ->createAnother(false)
                    ->action(function (array $data, DatabaseManagementService $service, RandomWordService $randomWordService) {
                        $data['database'] ??= $randomWordService->word() . random_int(1, 420);
                        $data['remote'] ??= '%';

                        $data['database'] = $service->generateUniqueDatabaseName($data['database'], $this->getOwnerRecord()->id);

                        try {
                            return $service->setValidateDatabaseLimit(false)->create($this->getOwnerRecord(), $data);
                        } catch (Exception $exception) {
                            Notification::make()
                                ->title(trans('admin/server.failed_to_create'))
                                ->body($exception->getMessage())
                                ->danger()
                                ->persistent()->send();

                            throw new Halt();
                        }
                    })
                    ->schema([
                        Select::make('database_host_id')
                            ->label(trans('admin/databasehost.model_label'))
                            ->required()
                            ->options(fn () => DatabaseHost::query()
                                ->whereHas('nodes', fn ($query) => $query->where('nodes.id', $this->getOwnerRecord()->node_id))
                                ->pluck('name', 'id')
                            )
                            ->selectablePlaceholder(false)
                            ->default(fn () => (DatabaseHost::query()->first())?->id),
                        TextInput::make('database')
                            ->label(trans('admin/server.name'))
                            ->alphaDash()
                            ->prefix(fn () => 's' . $this->getOwnerRecord()->id . '_')
                            ->hintIcon('tabler-question-mark', trans('admin/databasehost.table.name_helper')),
                        TextInput::make('remote')
                            ->columnSpan(1)
                            ->regex('/^[\w\-\/.%:]+$/')
                            ->label(trans('admin/databasehost.table.remote'))
                            ->default('%')
                            ->hintIcon('tabler-question-mark', trans('admin/databasehost.table.remote_helper')),
                    ]),
            ]);
    }
}
