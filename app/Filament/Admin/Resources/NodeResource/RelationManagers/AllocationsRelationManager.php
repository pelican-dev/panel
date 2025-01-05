<?php

namespace App\Filament\Admin\Resources\NodeResource\RelationManagers;

use App\Filament\Admin\Resources\ServerResource\Pages\CreateServer;
use App\Models\Allocation;
use App\Models\Node;
use App\Services\Allocations\AssignmentService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

/**
 * @method Node getOwnerRecord()
 */
class AllocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'allocations';

    protected static ?string $icon = 'tabler-plug-connected';

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
            ->recordTitleAttribute('ip')

            // Non Primary Allocations
            // ->checkIfRecordIsSelectableUsing(fn (Allocation $allocation) => $allocation->id !== $allocation->server?->allocation_id)

            // All assigned allocations
            ->checkIfRecordIsSelectableUsing(fn (Allocation $allocation) => $allocation->server_id === null)
            ->paginationPageOptions(['10', '20', '50', '100', '200', '500', '1000'])
            ->searchable()
            ->selectCurrentPageOnly() //Prevent people from trying to nuke 30,000 ports at once.... -,-
            ->columns([
                TextColumn::make('id')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                TextColumn::make('port')
                    ->searchable()
                    ->label('Port'),
                TextColumn::make('server.name')
                    ->label('Server')
                    ->icon('tabler-brand-docker')
                    ->visibleFrom('md')
                    ->searchable()
                    ->url(fn (Allocation $allocation): string => $allocation->server ? route('filament.admin.resources.servers.edit', ['record' => $allocation->server]) : ''),
                TextInputColumn::make('ip_alias')
                    ->searchable()
                    ->label('Alias'),
                TextInputColumn::make('ip')
                    ->searchable()
                    ->label('IP'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create new allocation')->label('Create Allocations')
                    ->form(fn () => [
                        Select::make('allocation_ip')
                            ->options(collect($this->getOwnerRecord()->ipAddresses())->mapWithKeys(fn (string $ip) => [$ip => $ip]))
                            ->label('IP Address')
                            ->inlineLabel()
                            ->ipv4()
                            ->helperText("Usually your machine's public IP unless you are port forwarding.")
                            ->afterStateUpdated(fn (Set $set) => $set('allocation_ports', []))
                            ->live()
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
                            ->disabled(fn (Get $get) => empty($get('allocation_ip')))
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('allocation_ports',
                                CreateServer::retrieveValidPorts($this->getOwnerRecord(), $state, $get('allocation_ip')))
                            )
                            ->splitKeys(['Tab', ' ', ','])
                            ->required(),
                    ])
                    ->action(fn (array $data, AssignmentService $service) => $service->handle($this->getOwnerRecord(), $data)),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('delete allocation')),
                ]),
            ]);
    }
}
