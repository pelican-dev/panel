<?php

namespace App\Filament\Admin\Resources\ServerResource\RelationManagers;

use App\Filament\Admin\Resources\ServerResource\Pages\CreateServer;
use App\Models\Allocation;
use App\Models\Server;
use App\Services\Allocations\AssignmentService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\DissociateBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

/**
 * @method Server getOwnerRecord()
 */
class AllocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'allocations';

    public function table(Table $table): Table
    {
        return $table
            ->selectCurrentPageOnly()
            ->recordTitleAttribute('address')
            ->recordTitle(fn (Allocation $allocation) => $allocation->address)
            ->inverseRelationship('server')
            ->heading(trans('admin/server.allocations'))
            ->columns([
                TextColumn::make('ip')
                    ->label(trans('admin/server.ip_address')),
                TextColumn::make('port')
                    ->label(trans('admin/server.port')),
                TextInputColumn::make('ip_alias')
                    ->label(trans('admin/server.alias')),
                TextInputColumn::make('notes')
                    ->label(trans('admin/server.notes'))
                    ->placeholder(trans('admin/server.no_notes')),
                IconColumn::make('primary')
                    ->icon(fn ($state) => match ($state) {
                        true => 'tabler-star-filled',
                        default => 'tabler-star',
                    })
                    ->color(fn ($state) => match ($state) {
                        true => 'warning',
                        default => 'gray',
                    })
                    ->tooltip(fn (Allocation $allocation) => trans('admin/server.' . ($allocation->id === $this->getOwnerRecord()->allocation_id ? 'already' : 'make') . '_primary'))
                    ->action(fn (Allocation $allocation) => $this->getOwnerRecord()->update(['allocation_id' => $allocation->id]) && $this->deselectAllTableRecords())
                    ->default(fn (Allocation $allocation) => $allocation->id === $this->getOwnerRecord()->allocation_id)
                    ->label(trans('admin/server.primary')),
            ])
            ->actions([
                DissociateAction::make()
                    ->after(function (Allocation $allocation) {
                        $allocation->update(['notes' => null]);
                        $this->getOwnerRecord()->allocation_id && $this->getOwnerRecord()->update(['allocation_id' => $this->getOwnerRecord()->allocations()->first()?->id]);
                    }),
            ])
            ->headerActions([
                CreateAction::make()->label(trans('admin/server.create_allocation'))
                    ->createAnother(false)
                    ->form(fn () => [
                        Select::make('allocation_ip')
                            ->options(collect($this->getOwnerRecord()->node->ipAddresses())->mapWithKeys(fn (string $ip) => [$ip => $ip]))
                            ->label(trans('admin/server.ip_address'))
                            ->inlineLabel()
                            ->ip()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('allocation_ports', []))
                            ->required(),
                        TextInput::make('allocation_alias')
                            ->label(trans('admin/server.alias'))
                            ->inlineLabel()
                            ->default(null)
                            ->helperText(trans('admin/server.alias_helper')),
                        TagsInput::make('allocation_ports')
                            ->placeholder('27015, 27017-27019')
                            ->label(trans('admin/server.ports'))
                            ->inlineLabel()
                            ->live()
                            ->disabled(fn (Get $get) => empty($get('allocation_ip')))
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('allocation_ports', CreateServer::retrieveValidPorts($this->getOwnerRecord()->node, $state, $get('allocation_ip'))))
                            ->splitKeys(['Tab', ' ', ','])
                            ->required(),
                    ])
                    ->action(fn (array $data, AssignmentService $service) => $service->handle($this->getOwnerRecord()->node, $data, $this->getOwnerRecord())),
                AssociateAction::make()
                    ->multiple()
                    ->associateAnother(false)
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn ($query) => $query->whereBelongsTo($this->getOwnerRecord()->node)->whereNull('server_id'))
                    ->recordSelectSearchColumns(['ip', 'port'])
                    ->label(trans('admin/server.add_allocation'))
                    ->after(fn (array $data) => !$this->getOwnerRecord()->allocation_id && $this->getOwnerRecord()->update(['allocation_id' => $data['recordId'][0]])),
            ])
            ->groupedBulkActions([
                DissociateBulkAction::make()
                    ->after(function () {
                        Allocation::whereNull('server_id')->update(['notes' => null]);
                        $this->getOwnerRecord()->allocation_id && $this->getOwnerRecord()->update(['allocation_id' => $this->getOwnerRecord()->allocations()->first()?->id]);
                    }),
            ]);
    }
}
