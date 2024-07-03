<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Models\Server;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Tables;

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
                Tables\Columns\TextColumn::make('condition')
                    ->default('unknown')
                    ->badge()
                    ->icon(fn (Server $server) => $server->conditionIcon())
                    ->color(fn (Server $server) => $server->conditionColor()),
                Tables\Columns\TextColumn::make('uuid')
                    ->hidden()
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node.name')
                    ->icon('tabler-server-2')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->hidden(fn (Table $table) => $table->getGrouping()?->getId() === 'node.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('egg.name')
                    ->icon('tabler-egg')
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->hidden(fn (Table $table) => $table->getGrouping()?->getId() === 'egg.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->icon('tabler-user')
                    ->label('Owner')
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->hidden(fn (Table $table) => $table->getGrouping()?->getId() === 'user.username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\SelectColumn::make('allocation_id')
                    ->label('Primary Allocation')
                    ->options(fn (Server $server) => $server->allocations->mapWithKeys(
                        fn ($allocation) => [$allocation->id => $allocation->address])
                    )
                    ->selectablePlaceholder(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('image')->hidden(),
                Tables\Columns\TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label('Backups')
                    ->icon('tabler-file-download')
                    ->numeric()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('View')
                    ->icon('tabler-terminal')
                    ->url(fn (Server $server) => "/server/$server->uuid_short"),
                Tables\Actions\EditAction::make(),
            ])
            ->emptyStateIcon('tabler-brand-docker')
            ->searchable()
            ->emptyStateDescription('')
            ->emptyStateHeading('No Servers')
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Create Server')
                    ->button(),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Server')
                ->hidden(fn () => Server::count() <= 0),
        ];
    }
}
