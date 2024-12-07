<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Enums\ServerState;
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

        return $table
            ->searchable(false)
            ->headerActions([
                Actions\Action::make('toggleSuspend')
                    ->hidden(fn () => $user->servers()
                        ->whereNot('status', ServerState::Suspended)
                        ->orWhereNull('status')
                        ->count() === 0
                    )
                    ->label('Suspend All Servers')
                    ->color('warning')
                    ->action(function (SuspensionService $suspensionService) use ($user) {
                        foreach ($user->servers()->whereNot('status', ServerState::Suspended)->get() as $server) {
                            $suspensionService->toggle($server);
                        }
                    }),
                Actions\Action::make('toggleUnsuspend')
                    ->hidden(fn () => $user->servers()->where('status', ServerState::Suspended)->count() === 0)
                    ->label('Unsuspend All Servers')
                    ->color('primary')
                    ->action(function (SuspensionService $suspensionService) use ($user) {
                        foreach ($user->servers()->where('status', ServerState::Suspended)->get() as $server) {
                            $suspensionService->toggle($server, SuspensionService::ACTION_UNSUSPEND);
                        }
                    }),
            ])
            ->columns([
                TextColumn::make('uuid')
                    ->hidden()
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->label(trans('strings.name'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('node.name')
                    ->icon('tabler-server-2')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->sortable(),
                TextColumn::make('egg.name')
                    ->icon('tabler-egg')
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->sortable(),
                SelectColumn::make('allocation.id')
                    ->label('Primary Allocation')
                    ->options(fn (Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
                TextColumn::make('image')->hidden(),
                TextColumn::make('databases_count')
                    ->counts('databases')
                    ->label('Databases')
                    ->icon('tabler-database')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label('Backups')
                    ->icon('tabler-file-download')
                    ->numeric()
                    ->sortable(),
            ]);
    }
}
