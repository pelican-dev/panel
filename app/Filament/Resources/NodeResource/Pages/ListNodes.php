<?php

namespace App\Filament\Resources\NodeResource\Pages;

use App\Filament\Resources\NodeResource;
use App\Models\Node;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Filament\Tables;

class ListNodes extends ListRecords
{
    protected static string $resource = NodeResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->checkIfRecordIsSelectableUsing(fn (Node $node) => $node->servers_count <= 0)
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\IconColumn::make('health')
                    ->alignCenter()
                    ->state(fn (Node $node) => $node)
                    ->view('livewire.columns.version-column'),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-server-2')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('fqdn')
                    ->visibleFrom('md')
                    ->label('Address')
                    ->icon('tabler-network')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('memory')
                    ->visibleFrom('sm')
                    ->icon('tabler-device-desktop-analytics')
                    ->numeric()
                    ->suffix(' GB')
                    ->formatStateUsing(fn ($state) => number_format($state / 1000, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('disk')
                    ->visibleFrom('sm')
                    ->icon('tabler-file')
                    ->numeric()
                    ->suffix(' GB')
                    ->formatStateUsing(fn ($state) => number_format($state / 1000, 2))
                    ->sortable(),
                Tables\Columns\IconColumn::make('scheme')
                    ->visibleFrom('xl')
                    ->label('SSL')
                    ->trueIcon('tabler-lock')
                    ->falseIcon('tabler-lock-open-off')
                    ->state(fn (Node $node) => $node->scheme === 'https'),
                Tables\Columns\IconColumn::make('public')
                    ->visibleFrom('lg')
                    ->trueIcon('tabler-eye-check')
                    ->falseIcon('tabler-eye-cancel'),
                Tables\Columns\TextColumn::make('servers_count')
                    ->visibleFrom('sm')
                    ->counts('servers')
                    ->label('Servers')
                    ->sortable()
                    ->icon('tabler-brand-docker'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
