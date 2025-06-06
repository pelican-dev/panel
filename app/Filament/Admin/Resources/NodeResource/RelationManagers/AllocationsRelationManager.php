<?php

namespace App\Filament\Admin\Resources\NodeResource\RelationManagers;

use App\Filament\Admin\Resources\ServerResource\Pages\CreateServer;
use App\Models\Allocation;
use App\Models\Node;
use App\Services\Allocations\AssignmentService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

/**
 * @method Node getOwnerRecord()
 */
class AllocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'allocations';

    protected static ?string $icon = 'tabler-plug-connected';

    public function setTitle(): string
    {
        return trans('admin/server.allocations');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('address')
            ->checkIfRecordIsSelectableUsing(fn (Allocation $allocation) => $allocation->server_id === null)
            ->paginationPageOptions(['10', '20', '50', '100', '200', '500'])
            ->searchable()
            ->heading('')
            ->selectCurrentPageOnly() //Prevent people from trying to nuke 30,000 ports at once.... -,-
            ->columns([
                TextColumn::make('id')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                TextColumn::make('port')
                    ->searchable()
                    ->label(trans('admin/node.ports')),
                TextColumn::make('server.name')
                    ->label(trans('admin/node.table.servers'))
                    ->icon('tabler-brand-docker')
                    ->visibleFrom('md')
                    ->searchable()
                    ->url(fn (Allocation $allocation): string => $allocation->server ? route('filament.admin.resources.servers.edit', ['record' => $allocation->server]) : ''),
                TextInputColumn::make('ip_alias')
                    ->searchable()
                    ->label(trans('admin/node.table.alias')),
                SelectColumn::make('ip')
                    ->options(fn (Allocation $allocation) => collect($this->getOwnerRecord()->ipAddresses())->merge([$allocation->ip])->mapWithKeys(fn (string $ip) => [$ip => $ip]))
                    ->selectablePlaceholder(false)
                    ->searchable()
                    ->label(trans('admin/node.table.ip')),
            ])
            ->headerActions([
                Action::make('create new allocation')
                    ->label(trans('admin/node.create_allocation'))
                    ->form(fn () => [
                        Select::make('allocation_ip')
                            ->options(collect($this->getOwnerRecord()->ipAddresses())->mapWithKeys(fn (string $ip) => [$ip => $ip]))
                            ->label(trans('admin/node.ip_address'))
                            ->inlineLabel()
                            ->ip()
                            ->helperText(trans('admin/node.ip_help'))
                            ->afterStateUpdated(fn (Set $set) => $set('allocation_ports', []))
                            ->live()
                            ->required(),
                        TextInput::make('allocation_alias')
                            ->label(trans('admin/node.table.alias'))
                            ->inlineLabel()
                            ->default(null)
                            ->helperText(trans('admin/node.alias_help'))
                            ->required(false),
                        TagsInput::make('allocation_ports')
                            ->placeholder('27015, 27017-27019')
                            ->label(trans('admin/node.ports'))
                            ->inlineLabel()
                            ->live()
                            ->disabled(fn (Get $get) => empty($get('allocation_ip')))
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('allocation_ports', CreateServer::retrieveValidPorts($this->getOwnerRecord(), $state, $get('allocation_ip'))))
                            ->splitKeys(['Tab', ' ', ','])
                            ->required(),
                    ])
                    ->action(fn (array $data, AssignmentService $service) => $service->handle($this->getOwnerRecord(), $data)),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->authorize(fn () => auth()->user()->can('update', $this->getOwnerRecord())),
            ]);
    }
}
