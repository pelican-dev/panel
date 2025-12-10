<?php

namespace App\Filament\Admin\Resources\Nodes\RelationManagers;

use App\Enums\ServerResourceType;
use App\Models\Server;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServersRelationManager extends RelationManager
{
    protected static string $relationship = 'servers';

    protected static string|\BackedEnum|null $icon = 'tabler-brand-docker';

    public function setTitle(): string
    {
        return trans('admin/node.table.servers');
    }

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->heading('')
            ->columns([
                TextColumn::make('user.username')
                    ->label(trans('admin/node.table.owner'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(trans('admin/node.table.name'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('egg.name')
                    ->label(trans('admin/node.table.egg'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->user]))
                    ->sortable(),
                SelectColumn::make('allocation.id')
                    ->label(trans('admin/node.primary_allocation'))
                    ->disabled(fn (Server $server) => $server->allocations->count() <= 1)
                    ->options(fn (Server $server) => $server->allocations->take(1)->mapWithKeys(fn ($allocation) => [$allocation->id => $allocation->address]))
                    ->selectablePlaceholder(fn (Server $server) => $server->allocations->count() <= 1)
                    ->placeholder(trans('admin/server.none'))
                    ->sortable(),
                TextColumn::make('cpu')
                    ->label(trans('admin/node.cpu'))
                    ->state(fn (Server $server) => $server->formatResource(ServerResourceType::CPULimit)),
                TextColumn::make('memory')
                    ->label(trans('admin/node.memory'))
                    ->state(fn (Server $server) => $server->formatResource(ServerResourceType::MemoryLimit)),
                TextColumn::make('disk')
                    ->label(trans('admin/node.disk'))
                    ->state(fn (Server $server) => $server->formatResource(ServerResourceType::DiskLimit)),
                TextColumn::make('databases_count')
                    ->counts('databases')
                    ->label(trans('admin/node.databases'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label(trans('admin/node.backups'))
                    ->numeric()
                    ->sortable(),
            ]);
    }
}
