<?php

namespace App\Filament\Resources\NodeResource\RelationManagers;

use App\Models\Server;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class NodesRelationManager extends RelationManager
{
    protected static string $relationship = 'servers';

    protected static ?string $icon = 'tabler-brand-docker';

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Owner')
                    ->icon('tabler-user')
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->url(fn (Server $server): string => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('egg.name')
                    ->icon('tabler-egg')
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->user]))
                    ->sortable(),
                Tables\Columns\SelectColumn::make('allocation.id')
                    ->label('Primary Allocation')
                    ->options(fn ($state, Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('memory')->icon('tabler-device-desktop-analytics'),
                Tables\Columns\TextColumn::make('cpu')->icon('tabler-cpu'),
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
