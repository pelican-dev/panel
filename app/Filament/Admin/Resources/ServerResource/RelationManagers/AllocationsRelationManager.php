<?php

namespace App\Filament\Admin\Resources\ServerResource\RelationManagers;

use App\Models\Allocation;
use App\Models\Server;
use App\Services\Allocations\AssignmentService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

/**
 * @method Server getOwnerRecord()
 */
class AllocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'allocations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ip')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->selectCurrentPageOnly()
            ->recordTitleAttribute('ip')
            ->recordTitle(fn (Allocation $allocation) => "$allocation->ip:$allocation->port")
            ->checkIfRecordIsSelectableUsing(fn (Allocation $record) => $record->id !== $this->getOwnerRecord()->allocation_id)
            ->inverseRelationship('server')
            ->columns([
                TextColumn::make('ip')->label('IP'),
                TextColumn::make('port')->label('Port'),
                TextInputColumn::make('ip_alias')->label('Alias'),
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
                    ->label('Primary'),
            ])
            ->actions([
                Action::make('make-primary')
                    ->action(fn (Allocation $allocation) => $this->getOwnerRecord()->update(['allocation_id' => $allocation->id]) && $this->deselectAllTableRecords())
                    ->label(fn (Allocation $allocation) => $allocation->id === $this->getOwnerRecord()->allocation_id ? '' : 'Make Primary'),
            ])
            ->headerActions([
                CreateAction::make()->label('Create Allocation')
                    ->createAnother(false)
                    ->form(fn () => [
                        Select::make('allocation_ip')
                            ->options(collect($this->getOwnerRecord()->node->ipAddresses())->mapWithKeys(fn (string $ip) => [$ip => $ip]))
                            ->label('IP Address')
                            ->inlineLabel()
                            ->ipv4()
                            ->helperText("Usually your machine's public IP unless you are port forwarding.")
                            ->required(),
                        TextInput::make('allocation_alias')
                            ->label('Alias')
                            ->inlineLabel()
                            ->default(null)
                            ->helperText('Optional display name to help you remember what these are.')
                            ->required(false),
                        TagsInput::make('allocation_ports')
                            ->placeholder('Examples: 27015, 27017-27019')
                            ->helperText(new HtmlString('
                                These are the ports that users can connect to this Server through.
                                <br />
                                You would have to port forward these on your home network.
                            '))
                            ->label('Ports')
                            ->inlineLabel()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $ports = collect();
                                $update = false;
                                foreach ($state as $portEntry) {
                                    if (!str_contains($portEntry, '-')) {
                                        if (is_numeric($portEntry)) {
                                            $ports->push((int) $portEntry);

                                            continue;
                                        }

                                        // Do not add non numerical ports
                                        $update = true;

                                        continue;
                                    }

                                    $update = true;
                                    [$start, $end] = explode('-', $portEntry);
                                    if (!is_numeric($start) || !is_numeric($end)) {
                                        continue;
                                    }

                                    $start = max((int) $start, 0);
                                    $end = min((int) $end, 2 ** 16 - 1);
                                    foreach (range($start, $end) as $i) {
                                        $ports->push($i);
                                    }
                                }

                                $uniquePorts = $ports->unique()->values();
                                if ($ports->count() > $uniquePorts->count()) {
                                    $update = true;
                                    $ports = $uniquePorts;
                                }

                                $sortedPorts = $ports->sort()->values();
                                if ($sortedPorts->all() !== $ports->all()) {
                                    $update = true;
                                    $ports = $sortedPorts;
                                }

                                $ports = $ports->filter(fn ($port) => $port > 1024 && $port < 65535)->values();

                                if ($update) {
                                    $set('allocation_ports', $ports->all());
                                }
                            })
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
                    ->label('Add Allocation'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                ]),
            ]);
    }
}
