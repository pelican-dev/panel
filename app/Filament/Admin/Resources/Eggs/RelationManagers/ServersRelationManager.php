<?php

namespace App\Filament\Admin\Resources\Eggs\RelationManagers;

use App\Models\Server;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServersRelationManager extends RelationManager
{
    protected static string $relationship = 'servers';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('servers')
            ->emptyStateDescription(trans('admin/egg.no_servers'))
            ->emptyStateHeading(trans('admin/egg.no_servers_help'))
            ->searchable(false)
            ->heading(trans('admin/egg.servers'))
            ->columns([
                TextColumn::make('user.username')
                    ->label(trans('admin/server.owner'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->sortable(),
                TextColumn::make('name')
                    ->label(trans('admin/server.name'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->sortable(),
                TextColumn::make('node.name')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node])),
                TextColumn::make('image')
                    ->label(trans('admin/server.docker_image')),
                SelectColumn::make('allocation.id')
                    ->label(trans('admin/server.primary_allocation'))
                    ->disabled()
                    ->options(fn (Server $server) => $server->allocations->take(1)->mapWithKeys(fn ($allocation) => [$allocation->id => $allocation->address]))
                    ->placeholder(trans('admin/server.none'))
                    ->sortable(),
            ]);
    }
}
