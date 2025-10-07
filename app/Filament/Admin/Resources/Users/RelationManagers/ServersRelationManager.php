<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Enums\ServerState;
use App\Enums\SuspendAction;
use App\Models\Server;
use App\Models\User;
use App\Services\Servers\SuspensionService;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServersRelationManager extends RelationManager
{
    protected static string $relationship = 'servers';

    public function table(Table $table): Table
    {
        /** @var User $user */
        $user = $this->getOwnerRecord();

        return $table
            ->searchable(false)
            ->heading(trans('admin/user.servers'))
            ->headerActions([
                Action::make('toggleSuspend')
                    ->hidden(fn () => $user->servers()
                        ->whereNot('status', ServerState::Suspended)
                        ->orWhereNull('status')
                        ->count() === 0
                    )
                    ->label(trans('admin/server.suspend_all'))
                    ->color('warning')
                    ->action(function (SuspensionService $suspensionService) use ($user) {
                        collect($user->servers)->filter(fn ($server) => !$server->isSuspended())
                            ->each(fn ($server) => $suspensionService->handle($server, SuspendAction::Suspend));
                    }),
                Action::make('toggleUnsuspend')
                    ->hidden(fn () => $user->servers()->where('status', ServerState::Suspended)->count() === 0)
                    ->label(trans('admin/server.unsuspend_all'))
                    ->color('primary')
                    ->action(function (SuspensionService $suspensionService) use ($user) {
                        collect($user->servers()->get())->filter(fn ($server) => $server->isSuspended())
                            ->each(fn ($server) => $suspensionService->handle($server, SuspendAction::Unsuspend));
                    }),
            ])
            ->columns([
                TextColumn::make('uuid')
                    ->hidden()
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('name')
                    ->label(trans('admin/server.name'))
                    ->url(fn (Server $server) => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('node.name')
                    ->label(trans('admin/server.node'))
                    ->url(fn (Server $server) => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->sortable(),
                TextColumn::make('egg.name')
                    ->label(trans('admin/server.egg'))
                    ->url(fn (Server $server) => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->sortable(),
                SelectColumn::make('allocation_id')
                    ->label(trans('admin/server.primary_allocation'))
                    ->hidden(fn () => !user()?->can('update server')) // TODO: update to policy check (fn (Server $server) --> $server is empty)
                    ->disabled(fn (Server $server) => $server->allocations->count() <= 1)
                    ->options(fn (Server $server) => $server->allocations->mapWithKeys(fn ($allocation) => [$allocation->id => $allocation->address]))
                    ->selectablePlaceholder(fn (Server $server) => $server->allocations->count() <= 1)
                    ->placeholder(trans('admin/server.none'))
                    ->sortable(),
                TextColumn::make('allocation_id_readonly')
                    ->label(trans('admin/server.primary_allocation'))
                    ->hidden(fn () => user()?->can('update server')) // TODO: update to policy check (fn (Server $server) --> $server is empty)
                    ->state(fn (Server $server) => $server->allocation->address ?? trans('admin/server.none')),
                TextColumn::make('databases_count')
                    ->counts('databases')
                    ->label(trans('admin/server.databases'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label(trans('admin/server.backups'))
                    ->numeric()
                    ->sortable(),
            ]);
    }
}
