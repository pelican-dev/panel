<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\ServerState;
use App\Models\Server;
use App\Models\User;
use App\Services\Servers\SuspensionService;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
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
            ->headerActions([
                Actions\Action::make('toggleSuspend')
                    ->hidden(fn () => $user->servers()
                        ->whereNot('status', ServerState::Suspended)
                        ->orWhereNull('status')
                        ->count() === 0
                    )
                    ->label('Suspend All Servers')
                    ->color('warning')
                    ->action(function () use ($user) {
                        foreach ($user->servers()->whereNot('status', ServerState::Suspended)->get() as $server) {
                            resolve(SuspensionService::class)->toggle($server);
                        }
                    }),

                Actions\Action::make('toggleUnsuspend')
                    ->hidden(fn () => $user->servers()->where('status', ServerState::Suspended)->count() === 0)
                    ->label('Unsuspend All Servers')
                    ->color('primary')
                    ->action(function () use ($user) {
                        foreach ($user->servers()->where('status', ServerState::Suspended)->get() as $server) {
                            resolve(SuspensionService::class)->toggle($server, SuspensionService::ACTION_UNSUSPEND);
                        }
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->hidden()
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->label(trans('strings.name'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node.name')
                    ->icon('tabler-server-2')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('egg.name')
                    ->icon('tabler-egg')
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->sortable(),
                Tables\Columns\SelectColumn::make('allocation.id')
                    ->label('Primary Allocation')
                    ->options(fn (Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('image')->hidden(),
                Tables\Columns\TextColumn::make('databases_count')
                    ->counts('databases')
                    ->label('Databases')
                    ->icon('tabler-database')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label('Backups')
                    ->icon('tabler-file-download')
                    ->numeric()
                    ->sortable(),
            ]);
    }
}
