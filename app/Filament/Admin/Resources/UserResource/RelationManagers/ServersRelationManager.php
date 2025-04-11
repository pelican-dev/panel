<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Enums\ServerState;
use App\Enums\SuspendAction;
use App\Models\Server;
use App\Models\User;
use App\Services\Servers\SuspensionService;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions;
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

        $servers = $user->accessibleServers();
        [$suspended, $unsuspended] = collect($servers->get())->partition(function ($server) {
            /** @var Server $server */
            return $server->status === ServerState::Suspended;
        });

        return $table
            ->query($servers)
            ->searchable(false)
            ->heading(trans('admin/user.servers'))
            ->headerActions([
                Actions\Action::make('toggleSuspend')
                    ->hidden(fn () => !$servers->count() || !$unsuspended->count())
                    ->label(trans('admin/server.suspend_all'))
                    ->color('warning')
                    ->action(fn (SuspensionService $suspensionService) => $unsuspended->each(fn ($server) => $suspensionService->handle($server, SuspendAction::Suspend))),
                Actions\Action::make('toggleUnsuspend')
                    ->hidden(fn () => !$servers->count() || !$suspended->count())
                    ->label(trans('admin/server.unsuspend_all'))
                    ->color('primary')
                    ->action(fn (SuspensionService $suspensionService) => $suspended->each(fn ($server) => $suspensionService->handle($server, SuspendAction::Unsuspend))),
            ])
            ->columns([
                TextColumn::make('uuid')
                    ->hidden()
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->label(trans('admin/server.name'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('node.name')
                    ->label(trans('admin/server.node'))
                    ->icon('tabler-server-2')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->sortable(),
                TextColumn::make('egg.name')
                    ->label(trans('admin/server.egg'))
                    ->icon('tabler-egg')
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->sortable(),
                SelectColumn::make('allocation.id')
                    ->label(trans('admin/server.primary_allocation'))
                    ->options(fn (Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
                TextColumn::make('image')->hidden(),
                TextColumn::make('databases_count')
                    ->counts('databases')
                    ->label(trans('admin/server.databases'))
                    ->icon('tabler-database')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label(trans('admin/server.backups'))
                    ->icon('tabler-file-download')
                    ->numeric()
                    ->sortable(),
            ]);
    }
}
