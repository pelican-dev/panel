<?php

namespace App\Filament\Resources\NodeResource\RelationManagers;

use App\Models\Allocation;
use App\Models\Node;
use App\Rules\Ip;
use App\Services\Allocations\AssignmentService;
use Exception;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
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

            // All assigned allocations
            ->checkIfRecordIsSelectableUsing(fn (Allocation $allocation) => $allocation->server_id === null && $allocation->id !== $allocation->server?->allocation_id)
            ->searchable()
            ->selectCurrentPageOnly() //Prevent people from trying to nuke 30,000 ports at once.... -,-
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('port')
                    ->searchable()
                    ->label('Port'),
                TextColumn::make('server.name')
                    ->label('Server')
                    ->icon('tabler-brand-docker')
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
                        TextInput::make('allocation_ip')
                            ->datalist($this->getOwnerRecord()->ipAddresses())
                            ->label('IP Address')
                            ->inlineLabel()
                            ->rules([new Ip()])
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
                            ->splitKeys(['Tab', ' ', ','])
                            ->required()
                            ->afterStateUpdated(function (array $state, Set $set) {
                                $ports = collect($state)
                                    ->flatMap(function ($portEntry) {
                                        if (preg_match(AssignmentService::PORT_RANGE_REGEX, $portEntry, $matches)) {
                                            array_shift($matches);

                                            [$start, $end] = $matches;

                                            if ($start > $end) {
                                                [$start, $end] = [$end, $start];
                                            }

                                            return range((int) $start, (int) $end);
                                        }

                                        if (is_numeric($portEntry)) {
                                            return (int) $portEntry;
                                        }
                                    })->unique()->all();

                                if (count($ports) > count($state)) {
                                    $set('allocation_ports', $ports);
                                }
                            }),
                    ])
                    ->action(function (array $data, AssignmentService $service) {
                        try {
                            $service->handle($this->getOwnerRecord(), $data);
                        } catch (Exception $exception) {
                            report($exception);

                            Notification::make()
                                ->title(str($exception::class)->afterLast('\\'))
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('delete allocation')),
                ]),
            ]);
    }
}
