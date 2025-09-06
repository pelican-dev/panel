<?php

namespace App\Filament\Server\Resources\DatabaseResource\Pages;

use App\Filament\Server\Resources\DatabaseResource;
use App\Models\DatabaseHost;
use App\Models\Server;
use App\Services\Databases\DatabaseManagementService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;

class ListDatabases extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = DatabaseResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            CreateAction::make('new')
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon(fn () => $server->databases()->count() >= $server->database_limit ? 'tabler-database-x' : 'tabler-database-plus')
                ->tooltip(fn () => $server->databases()->count() >= $server->database_limit ? trans('server/database.limit') : trans('server/database.create_database'))
                ->disabled(fn () => $server->databases()->count() >= $server->database_limit)
                ->color(fn () => $server->databases()->count() >= $server->database_limit ? 'danger' : 'primary')
                ->createAnother(false)
                ->form([
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
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip(trans('server/database.name_hint')),
                            TextInput::make('remote')
                                ->label(trans('server/database.connections_from'))
                                ->columnSpan(1)
                                ->default('%'),
                        ]),
                ])
                ->action(function ($data, DatabaseManagementService $service) use ($server) {
                    $data['database'] ??= str_random(12);
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
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return trans('server/database.title');
    }
}
