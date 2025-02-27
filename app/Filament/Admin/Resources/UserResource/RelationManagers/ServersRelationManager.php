<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Models\User;
use App\Models\Server;
use App\Enums\ServerState;
use Filament\Tables\Table;
use App\Enums\SuspendAction;
use Filament\Tables\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use App\Services\Servers\SuspensionService;
use Filament\Resources\RelationManagers\RelationManager;

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
                Actions\Action::make('toggleSuspend')
                    ->hidden(
                        fn () => $user->servers()
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
                Actions\Action::make('toggleUnsuspend')
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
