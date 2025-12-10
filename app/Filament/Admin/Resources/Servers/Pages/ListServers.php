<?php

namespace App\Filament\Admin\Resources\Servers\Pages;

use App\Filament\Admin\Resources\Servers\ServerResource;
use App\Filament\Server\Pages\Console;
use App\Models\Server;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListServers extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

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
                    ->searchable(query: fn (Builder $query, string $search) => $query->where(
                        Server::query()->qualifyColumn('name'), 'like', "%{$search}%")
                    )
                    ->sortable(),
                TextColumn::make('node.name')
                    ->label(trans('admin/server.node'))
                    ->url(fn (Server $server) => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->hidden(fn (Table $table) => $table->getGrouping()?->getId() === 'node.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('egg.name')
                    ->label(trans('admin/server.egg'))
                    ->url(fn (Server $server) => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->hidden(fn (Table $table) => $table->getGrouping()?->getId() === 'egg.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.username')
                    ->label(trans('admin/user.username'))
                    ->url(fn (Server $server) => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->hidden(fn (Table $table) => $table->getGrouping()?->getId() === 'user.username')
                    ->sortable()
                    ->searchable(),
                SelectColumn::make('allocation_id')
                    ->label(trans('admin/server.primary_allocation'))
                    ->hidden(fn () => !user()?->can('update server')) // TODO: update to policy check (fn (Server $server) --> $server is empty)
                    ->disabled(fn (Server $server) => $server->allocations->count() <= 1)
                    ->options(fn (Server $server) => $server->allocations->mapWithKeys(fn ($allocation) => [$allocation->id => $allocation->address]))
                    ->selectablePlaceholder(fn (Server $server) => $server->allocations->count() <= 1)
                    ->placeholder(trans('admin/server.none'))
                    ->sortable(),
                TextColumn::make('allocation_id_readonly')
                    ->label(trans('admin/server.primary_allocation'))
                    ->hidden(fn () => user()?->can('update server')) // TODO: update to policy check (fn (Server $server) --> $server is empty)
                    ->state(fn (Server $server) => $server->allocation->address ?? trans('admin/server.none')),
                TextColumn::make('image')->hidden(),
                TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label(trans('admin/server.backups'))
                    ->numeric()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('View')
                    ->label(trans('admin/server.view'))
                    ->iconButton()
                    ->icon('tabler-terminal')
                    ->iconSize(IconSize::Large)
                    ->url(fn (Server $server) => Console::getUrl(panel: 'server', tenant: $server))
                    ->authorize(fn (Server $server) => user()?->canAccessTenant($server)),
                EditAction::make(),
            ])
            ->emptyStateIcon('tabler-brand-docker')
            ->searchable()
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/server.no_servers'));
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-file-plus'),
        ];
    }
}
