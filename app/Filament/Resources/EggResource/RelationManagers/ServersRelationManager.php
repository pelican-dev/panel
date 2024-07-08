<?php

namespace App\Filament\Resources\EggResource\RelationManagers;

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
            ->emptyStateDescription('No Servers')->emptyStateHeading('No servers are assigned this egg.')
            ->searchable(false)
            ->columns([
                TextColumn::make('user.username')
                    ->label('Owner')
                    ->icon('tabler-user')
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->sortable(),
                TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->url(fn (Server $server): string => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->sortable(),
                TextColumn::make('node.name')
                    ->icon('tabler-server-2')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node])),
                TextColumn::make('image')
                    ->label('Docker Image'),
                SelectColumn::make('allocation.id')
                    ->label('Primary Allocation')
                    ->options(fn (Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
            ]);
    }
}
