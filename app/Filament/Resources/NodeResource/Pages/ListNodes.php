<?php

namespace App\Filament\Resources\NodeResource\Pages;

use App\Filament\Resources\NodeResource;
use App\Models\Node;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListNodes extends ListRecords
{
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
                IconColumn::make('health')
                    ->alignCenter()
                    ->state(fn (Node $node) => $node)
                    ->view('livewire.columns.version-column'),
                TextColumn::make('name')
                    ->icon('tabler-server-2')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fqdn')
                    ->visibleFrom('md')
                    ->label('Address')
                    ->icon('tabler-network')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('memory')
                    ->visibleFrom('sm')
                    ->icon('tabler-device-desktop-analytics')
                    ->numeric()
                    ->suffix(config('panel.use_binary_prefix') ? ' GiB' : ' GB')
                    ->formatStateUsing(fn ($state) => number_format($state / (config('panel.use_binary_prefix') ? 1024 : 1000), 2))
                    ->sortable(),
                TextColumn::make('disk')
                    ->visibleFrom('sm')
                    ->icon('tabler-file')
                    ->numeric()
                    ->suffix(config('panel.use_binary_prefix') ? ' GiB' : ' GB')
                    ->formatStateUsing(fn ($state) => number_format($state / (config('panel.use_binary_prefix') ? 1024 : 1000), 2))
                    ->sortable(),
                TextColumn::make('cpu')
                    ->visibleFrom('sm')
                    ->icon('tabler-file')
                    ->numeric()
                    ->suffix(' %')
                    ->sortable(),
                IconColumn::make('scheme')
                    ->visibleFrom('xl')
                    ->label('SSL')
                    ->trueIcon('tabler-lock')
                    ->falseIcon('tabler-lock-open-off')
                    ->state(fn (Node $node) => $node->scheme === 'https'),
                IconColumn::make('public')
                    ->visibleFrom('lg')
                    ->trueIcon('tabler-eye-check')
                    ->falseIcon('tabler-eye-cancel'),
                TextColumn::make('servers_count')
                    ->visibleFrom('sm')
                    ->counts('servers')
                    ->label('Servers')
                    ->sortable()
                    ->icon('tabler-brand-docker'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('tabler-server-2')
            ->emptyStateDescription('')
            ->emptyStateHeading('No Nodes')
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Create Node')
                    ->button(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Node')
                ->hidden(fn () => Node::count() <= 0),
        ];
    }
}
