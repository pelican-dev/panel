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
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\DissociateBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

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
            ->checkIfRecordIsSelectableUsing(fn (Allocation $record) => $record->id !== $this->getOwnerRecord()->allocation_id)
            ->inverseRelationship('server')
            ->heading(trans('admin/server.allocations'))
            ->columns([
                TextColumn::make('ip')
                    ->label(trans('admin/server.ip_address')),
                TextColumn::make('port')
                    ->label(trans('admin/server.port')),
                TextInputColumn::make('ip_alias')
                    ->label(trans('admin/server.alias')),
                IconColumn::make('primary')
                    ->icon(fn ($state) => match ($state) {
                        true => 'tabler-star-filled',
                        default => 'tabler-star',
                    })
                    ->color(fn ($state) => match ($state) {
                        true => 'warning',
                        default => 'gray',
                    })
                    ->action(fn (Allocation $allocation) => $this->getOwnerRecord()->update(['allocation_id' => $allocation->id]) && $this->deselectAllTableRecords())
                    ->default(fn (Allocation $allocation) => $allocation->id === $this->getOwnerRecord()->allocation_id)
                    ->label(trans('admin/server.primary')),
            ])
            ->actions([
                Action::make('make-primary')
                    ->label(trans('admin/server.make_primary'))
                    ->action(fn (Allocation $allocation) => $this->getOwnerRecord()->update(['allocation_id' => $allocation->id]) && $this->deselectAllTableRecords())
                    ->hidden(fn (Allocation $allocation) => $allocation->id === $this->getOwnerRecord()->allocation_id),
                DissociateAction::make()
                    ->hidden(fn (Allocation $allocation) => $allocation->id === $this->getOwnerRecord()->allocation_id),
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
                            ->helperText(trans('admin/server.alias_helper'))
                            ->required(false),
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
                    ->label(trans('admin/server.add_allocation')),
            ])
            ->groupedBulkActions([
                DissociateBulkAction::make()
                    ->before(function (DissociateBulkAction $action, Collection $records) {
                        $records = $records->filter(function ($allocation) {
                            /** @var Allocation $allocation */
                            return $allocation->id !== $this->getOwnerRecord()->allocation_id;
                        });

                        if ($records->isEmpty()) {
                            $action->failureNotificationTitle(trans('admin/server.notifications.dissociate_primary'))->failure();
                            throw new Halt();
                        }

                        return $records;
                    }),
            ]);
    }
}
