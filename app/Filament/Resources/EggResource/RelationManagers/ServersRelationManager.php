<?php

namespace App\Filament\Resources\EggResource\RelationManagers;

use App\Models\Server;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ServersRelationManager extends RelationManager
{
    protected static string $relationship = 'servers';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('servers')
            ->emptyStateDescription('No Servers')->emptyStateHeading('No servers are assigned this egg.')
            ->searchable(false)
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Owner')
                    ->icon('tabler-user')
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->url(fn (Server $server): string => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('node.name')
                    ->icon('tabler-server-2')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node])),
                Tables\Columns\TextColumn::make('image')
                    ->label('Docker Image'),
                Tables\Columns\SelectColumn::make('allocation.id')
                    ->label('Primary Allocation')
                    ->options(fn ($state, Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
            ]);
    }
}
