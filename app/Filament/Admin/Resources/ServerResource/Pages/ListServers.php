<?php

namespace App\Filament\Admin\Resources\ServerResource\Pages;

use App\Filament\Server\Pages\Console;
use App\Filament\Admin\Resources\ServerResource;
use App\Models\Server;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ListServers extends ListRecords
{
    protected static string $resource = ServerResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->defaultGroup('node.name')
            ->groups([
                Group::make('node.name')->getDescriptionFromRecordUsing(fn (Server $server): string => str($server->node->description)->limit(150)),
                Group::make('user.username')->getDescriptionFromRecordUsing(fn (Server $server): string => $server->user->email),
                Group::make('egg.name')->getDescriptionFromRecordUsing(fn (Server $server): string => str($server->egg->description)->limit(150)),
            ])
            ->columns([
                TextColumn::make('condition')
                    ->label(trans('admin/server.condition'))
                    ->default('unknown')
                    ->badge()
                    ->icon(fn (Server $server) => $server->condition->getIcon())
                    ->color(fn (Server $server) => $server->condition->getColor()),
                TextColumn::make('uuid')
                    ->hidden()
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('name')
                    ->label(trans('admin/server.name'))
                    ->icon('tabler-brand-docker')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('node.name')
                    ->label(trans('admin/server.node'))
                    ->icon('tabler-server-2')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->hidden(fn (Table $table) => $table->getGrouping()?->getId() === 'node.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('egg.name')
                    ->icon('tabler-egg')
                    ->label(trans('admin/server.egg'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->hidden(fn (Table $table) => $table->getGrouping()?->getId() === 'egg.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.username')
                    ->icon('tabler-user')
                    ->label(trans('admin/user.username'))
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->hidden(fn (Table $table) => $table->getGrouping()?->getId() === 'user.username')
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('allocation_id')
                    ->label(trans('admin/server.primary_allocation'))
                    ->hidden(!auth()->user()->can('update server')) // TODO: update to policy check (fn (Server $server) --> $server is empty)
                    ->options(fn (Server $server) => $server->allocations->mapWithKeys(fn ($allocation) => [$allocation->id => $allocation->address]))
                    ->selectablePlaceholder(false)
                    ->sortable(),
                TextColumn::make('allocation_id_readonly')
                    ->label(trans('admin/server.primary_allocation'))
                    ->hidden(auth()->user()->can('update server')) // TODO: update to policy check (fn (Server $server) --> $server is empty)
                    ->state(fn (Server $server) => $server->allocation->address),
                TextColumn::make('image')->hidden(),
                TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label(trans('admin/server.backups'))
                    ->icon('tabler-file-download')
                    ->numeric()
                    ->sortable(),
            ])
            ->actions([
                Action::make('View')
                    ->label(trans('admin/server.view'))
                    ->icon('tabler-terminal')
                    ->url(fn (Server $server) => Console::getUrl(panel: 'server', tenant: $server))
                    ->authorize(fn (Server $server) => auth()->user()->canAccessTenant($server)),
                EditAction::make(),
            ])
            ->emptyStateIcon('tabler-brand-docker')
            ->searchable()
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/server.no_servers'))
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->hidden(fn () => Server::count() <= 0),
        ];
    }
}
