<?php

namespace App\Filament\Admin\Resources\Nodes\Pages;

use App\Filament\Admin\Resources\Nodes\NodeResource;
use App\Filament\Components\Tables\Columns\NodeHealthColumn;
use App\Filament\Components\Tables\Filters\TagsFilter;
use App\Models\Node;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListNodes extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = NodeResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->checkIfRecordIsSelectableUsing(fn (Node $node) => $node->servers_count <= 0)
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable()
                    ->hidden(),
                NodeHealthColumn::make('health'),
                TextColumn::make('name')
                    ->label(trans('admin/node.table.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fqdn')
                    ->visibleFrom('md')
                    ->label(trans('admin/node.table.address'))
                    ->sortable()
                    ->searchable(),
                IconColumn::make('scheme')
                    ->visibleFrom('xl')
                    ->label('SSL')
                    ->trueIcon('tabler-lock')
                    ->falseIcon('tabler-lock-open-off')
                    ->state(fn (Node $node) => $node->scheme === 'https'),
                IconColumn::make('public')
                    ->label(trans('admin/node.table.public'))
                    ->visibleFrom('lg')
                    ->trueIcon('tabler-eye-check')
                    ->falseIcon('tabler-eye-cancel'),
                TextColumn::make('servers_count')
                    ->visibleFrom('sm')
                    ->counts('servers')
                    ->label(trans('admin/node.table.servers'))
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->emptyStateIcon('tabler-server-2')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/node.no_nodes'))
            ->filters([
                TagsFilter::make()
                    ->model(Node::class),
            ]);
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
