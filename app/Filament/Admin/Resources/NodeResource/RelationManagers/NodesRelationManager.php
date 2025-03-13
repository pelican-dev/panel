<?php

namespace App\Filament\Admin\Resources\NodeResource\RelationManagers;

use App\Models\Server;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NodesRelationManager extends RelationManager
{
    protected static string $relationship = 'servers';

    protected static ?string $icon = 'tabler-brand-docker';

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
                    ->icon('tabler-user')
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(trans('admin/node.table.name'))
                    ->icon('tabler-brand-docker')
                    ->url(fn (Server $server): string => route('filament.admin.resources.servers.edit', ['record' => $server]))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('egg.name')
                    ->label(trans('admin/node.table.egg'))
                    ->icon('tabler-egg')
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->user]))
                    ->sortable(),
                SelectColumn::make('allocation.id')
                    ->label(trans('admin/node.primary_allocation'))
                    ->options(fn (Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
                TextColumn::make('memory')->label(trans('admin/node.memory'))->icon('tabler-device-desktop-analytics'),
                TextColumn::make('cpu')->label(trans('admin/node.cpu'))->icon('tabler-cpu'),
                TextColumn::make('databases_count')
                    ->counts('databases')
                    ->label(trans('admin/node.databases'))
                    ->icon('tabler-database')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label(trans('admin/node.backups'))
                    ->icon('tabler-file-download')
                    ->numeric()
                    ->sortable(),
            ]);
    }
}
