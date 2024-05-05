<?php

namespace App\Filament\Resources\NodeResource\RelationManagers;

use App\Models\Allocation;
use App\Models\Server;
use App\Services\Allocations\AssignmentService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class AllocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'allocations';

    protected static ?string $icon = 'tabler-plug-connected';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ip')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ip')

            // Non Primary Allocations
            // ->checkIfRecordIsSelectableUsing(fn (Allocation $allocation) => $allocation->id !== $allocation->server?->allocation_id)

            // All assigned allocations
            ->checkIfRecordIsSelectableUsing(fn (Allocation $allocation) => $allocation->server_id === null)
            ->searchable()
            ->columns([
                Tables\Columns\TextColumn::make('server.name')
                    ->label('Server')
                    ->icon('tabler-brand-docker')
                    ->url(fn (Allocation $allocation): string => $allocation->server ? route('filament.admin.resources.servers.edit', ['record' => $allocation->server]) : ''),
                Tables\Columns\TextColumn::make('ip_alias')
                    ->label('Alias'),
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP'),
                Tables\Columns\TextColumn::make('port')
                    ->searchable()
                    ->label('Port'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('create new allocation')->label('Create Allocations')
                    ->form(fn () => [
                        Forms\Components\TextInput::make('allocation_ip')
                            ->datalist($this->getOwnerRecord()->ipAddresses() ?? [])
                            ->label('IP Address')
                            ->inlineLabel()
                            ->ipv4()
                            ->helperText("Usually your machine's public IP unless you are port forwarding.")
                            ->required(),
                        Forms\Components\TextInput::make('allocation_alias')
                            ->label('Alias')
                            ->inlineLabel()
                            ->default(null)
                            ->helperText('Optional display name to help you remember what these are.')
                            ->required(false),
                        Forms\Components\TagsInput::make('allocation_ports')
                            ->placeholder('Examples: 27015, 27017-27019')
                            ->helperText(new HtmlString('
                                These are the ports that users can connect to this Server through.
                                <br />
                                You would have to port forward these on your home network.
                            '))
                            ->label('Ports')
                            ->inlineLabel()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
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
                                    for ($i = $start; $i <= $end; $i++) {
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

                                if ($update) {
                                    $set('allocation_ports', $ports->all());
                                }
                            })
                            ->splitKeys(['Tab', ' ', ','])
                            ->required(),
                    ])
                    ->action(fn (array $data) => resolve(AssignmentService::class)->handle($this->getOwnerRecord(), $data)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
