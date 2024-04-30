<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Models\Server;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Filament\Tables;

class ListServers extends ListRecords
{
    protected static string $resource = ServerResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->default('unknown')
                    ->badge()
                    ->default(function (Server $server) {
                        if ($server->status !== null) {
                            return $server->status;
                        }

                        return $server->retrieveStatus() ?? 'node_fail';
                    })
                    ->icon(fn ($state) => match ($state) {
                        'node_fail' => 'tabler-server-off',
                        'running' => 'tabler-heartbeat',
                        'removing' => 'tabler-heart-x',
                        'offline' => 'tabler-heart-off',
                        'paused' => 'tabler-heart-pause',
                        'installing' => 'tabler-heart-bolt',
                        'suspended' => 'tabler-heart-cancel',
                        default => 'tabler-heart-question',
                    })
                    ->color(fn ($state): string => match ($state) {
                        'running' => 'success',
                        'installing', 'restarting' => 'primary',
                        'paused', 'removing' => 'warning',
                        'node_fail', 'install_failed', 'suspended' => 'danger',
                        default => 'gray',
                    }),

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
                    ->sortable(),
                Tables\Columns\TextColumn::make('egg.name')
                    ->icon('tabler-egg')
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->icon('tabler-user')
                    ->label('Owner')
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->sortable(),
                Tables\Columns\SelectColumn::make('allocation_id')
                    ->label('Primary Allocation')
                    ->options(fn ($state, Server $server) => $server->allocations->mapWithKeys(
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('tabler-brand-docker')
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
