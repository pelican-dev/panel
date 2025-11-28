<?php

namespace App\Filament\Admin\Resources\Servers\RelationManagers;

use App\Filament\Admin\Resources\Servers\Pages\CreateServer;
use App\Models\Allocation;
use App\Models\Server;
use App\Services\Allocations\AssignmentService;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\IconSize;
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
            ->heading('')
            ->selectCurrentPageOnly()
            ->recordTitleAttribute('address')
            ->recordTitle(fn (Allocation $allocation) => $allocation->address)
            ->inverseRelationship('server')
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
                IconColumn::make('is_locked')
                    ->label(trans('admin/server.locked'))
                    ->tooltip(trans('admin/server.locked_helper'))
                    ->trueIcon('tabler-lock')
                    ->falseIcon('tabler-lock-open'),
            ])
            ->recordActions([
                Action::make('make-primary')
                    ->label(trans('admin/server.make_primary'))
                    ->action(fn (Allocation $allocation) => $this->getOwnerRecord()->update(['allocation_id' => $allocation->id]) && $this->deselectAllTableRecords())
                    ->hidden(fn (Allocation $allocation) => $allocation->id === $this->getOwnerRecord()->allocation_id),
                Action::make('lock')
                    ->label(trans('admin/server.lock'))
                    ->action(fn (Allocation $allocation) => $allocation->update(['is_locked' => true]) && $this->deselectAllTableRecords())
                    ->hidden(fn (Allocation $allocation) => $allocation->is_locked),
                Action::make('unlock')
                    ->label(trans('admin/server.unlock'))
                    ->action(fn (Allocation $allocation) => $allocation->update(['is_locked' => false]) && $this->deselectAllTableRecords())
                    ->visible(fn (Allocation $allocation) => $allocation->is_locked),
                DissociateAction::make()
                    ->after(function (Allocation $allocation) {
                        $allocation->update([
                            'notes' => null,
                            'is_locked' => false,
                        ]);

                        if (!$this->getOwnerRecord()->allocation_id) {
                            $this->getOwnerRecord()->update(['allocation_id' => $this->getOwnerRecord()->allocations()->first()?->id]);
                        }
                    }),
            ])
            ->toolbarActions([
                DissociateBulkAction::make()
                    ->after(function () {
                        Allocation::whereNull('server_id')->update([
                            'notes' => null,
                            'is_locked' => false,
                        ]);

                        if (!$this->getOwnerRecord()->allocation_id) {
                            $this->getOwnerRecord()->update(['allocation_id' => $this->getOwnerRecord()->allocations()->first()?->id]);
                        }
                    }),
                CreateAction::make()
                    ->label(trans('admin/server.create_allocation'))
                    ->icon('tabler-network')
                    ->iconButton()->iconSize(IconSize::ExtraLarge)
                    ->createAnother(false)
                    ->schema(fn () => [
                        Select::make('allocation_ip')
                            ->options(fn () => collect($this->getOwnerRecord()->node->ipAddresses())->mapWithKeys(fn (string $ip) => [$ip => $ip]))
                            ->label(trans('admin/server.ip_address'))
                            ->inlineLabel()
                            ->ip()
                            ->live()
                            ->hintAction(
                                Action::make('refresh')
                                    ->iconButton()
                                    ->icon('tabler-refresh')
                                    ->tooltip(trans('admin/node.refresh'))
                                    ->action(function () {
                                        cache()->forget("nodes.{$this->getOwnerRecord()->node->id}.ips");
                                    })
                            )
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
                        Hidden::make('is_locked')
                            ->default(true),
                    ])
                    ->action(fn (array $data, AssignmentService $service) => $service->handle($this->getOwnerRecord()->node, $data, $this->getOwnerRecord())),
                AssociateAction::make()
                    ->icon('tabler-file-plus')
                    ->iconButton()->iconSize(IconSize::ExtraLarge)
                    ->multiple()
                    ->associateAnother(false)
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn ($query) => $query->whereBelongsTo($this->getOwnerRecord()->node)->whereNull('server_id'))
                    ->recordSelectSearchColumns(['ip', 'port'])
                    ->label(trans('admin/server.add_allocation'))
                    ->after(function (array $data) {
                        Allocation::whereIn('id', array_values(array_unique($data['recordId'])))->update(['is_locked' => true]);

                        if (!$this->getOwnerRecord()->allocation_id) {
                            $this->getOwnerRecord()->update(['allocation_id' => $data['recordId'][0]]);
                        }
                    }),
            ]);
    }
}
