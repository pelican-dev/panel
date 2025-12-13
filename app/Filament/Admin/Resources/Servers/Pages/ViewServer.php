<?php

namespace App\Filament\Admin\Resources\Servers\Pages;

use App\Filament\Admin\Resources\Servers\RelationManagers\AllocationsRelationManager;
use App\Filament\Admin\Resources\Servers\RelationManagers\DatabasesRelationManager;
use App\Filament\Admin\Resources\Servers\ServerResource;
use App\Filament\Server\Pages\Console;
use App\Models\Server;
use App\Services\Servers\ServerDeletionService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Illuminate\Http\Client\ConnectionException;
use Predis\Connection\ConnectionException as PredisConnectionException;

class ViewServer extends ViewRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = ServerResource::class;

    /**
     * @throws \Random\RandomException
     * @throws \Exception
     */
    public function form(Schema $schema): Schema
    {
        return ServerResource::schema($schema);
    }

    public function getRelationManagers(): array
    {
        return [
            AllocationsRelationManager::class,
            DatabasesRelationManager::class,
        ];
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        /** @var Server $server */
        $server = $this->getRecord();

        $canForceDelete = cache()->get("servers.$server->uuid.canForceDelete", false);

        return [
            Action::make('console')
                ->label(trans('admin/server.console'))
                ->icon('tabler-terminal')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->url(fn (Server $server) => Console::getUrl(panel: 'server', tenant: $server)),
            Action::make('Delete')
                ->color('danger')
                ->label(trans('filament-actions::delete.single.label'))
                ->modalHeading(trans('filament-actions::delete.single.modal.heading', ['label' => $server->name]))
                ->modalSubmitActionLabel(trans('filament-actions::delete.single.label'))
                ->requiresConfirmation()
                ->action(function (Server $server, ServerDeletionService $service) {
                    try {
                        $service->handle($server);

                        return redirect(ListServers::getUrl(panel: 'admin'));
                    } catch (ConnectionException|PredisConnectionException) {
                        cache()->put("servers.$server->uuid.canForceDelete", true, now()->addMinutes(5));

                        return Notification::make()
                            ->title(trans('admin/server.notifications.error_server_delete'))
                            ->body(trans('admin/server.notifications.error_server_delete_body'))
                            ->color('warning')
                            ->icon('tabler-database')
                            ->warning()
                            ->send();
                    }
                })
                ->hidden(fn () => $canForceDelete)
                ->authorize(fn (Server $server) => user()?->can('delete server', $server))
                ->icon('tabler-trash')
                ->iconButton()->iconSize(IconSize::ExtraLarge),
            Action::make('ForceDelete')
                ->color('danger')
                ->label(trans('filament-actions::force-delete.single.label'))
                ->modalHeading(trans('filament-actions::force-delete.single.modal.heading', ['label' => $server->name]))
                ->modalSubmitActionLabel(trans('filament-actions::force-delete.single.label'))
                ->requiresConfirmation()
                ->action(function (Server $server, ServerDeletionService $service) {
                    try {
                        $service->withForce()->handle($server);

                        return redirect(ListServers::getUrl(panel: 'admin'));
                    } catch (ConnectionException|PredisConnectionException) {
                        return cache()->forget("servers.$server->uuid.canForceDelete");
                    }
                })
                ->visible(fn () => $canForceDelete)
                ->authorize(fn (Server $server) => user()?->can('delete server', $server)),
            EditAction::make()
                ->icon('tabler-pencil')
                ->iconButton()->iconSize(IconSize::ExtraLarge),
        ];
    }

    /** @return array<mixed> */
    protected function getFormActions(): array
    {
        return [];
    }

    /** @param array<mixed> $data
     * @return array<mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['description'])) {
            $data['description'] = '';
        }

        unset($data['docker'], $data['status'], $data['allocation_id']);

        return $data;
    }
}
