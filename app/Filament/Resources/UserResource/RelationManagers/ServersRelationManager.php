<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Server;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class ServersRelationManager extends RelationManager
{
    protected static string $relationship = 'servers';

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->hidden()
                    ->label(trans('strings.uuid'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->label(trans('strings.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node.name')
                    ->icon('tabler-server-2')
                    ->label(trans_choice('strings.nodes', 2))
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('egg.name')
                    ->icon('tabler-egg')
                    ->label(trans_choice('strings.eggs', 2))
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->sortable(),
                Tables\Columns\SelectColumn::make('allocation.id')
                    ->label(trans('strings.primary_allocation'))
                    ->options(fn ($state, Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('image')->hidden(),
                Tables\Columns\TextColumn::make('databases_count')
                    ->counts('databases')
                    ->label(trans_choice('strings.databases', 2))
                    ->icon('tabler-database')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label(trans_choice('strings.backups', 2))
                    ->icon('tabler-file-download')
                    ->numeric()
                    ->sortable(),
            ]);
    }
}
