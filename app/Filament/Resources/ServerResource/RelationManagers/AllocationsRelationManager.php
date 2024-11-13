<?php

namespace App\Filament\Resources\ServerResource\RelationManagers;

use App\Models\Allocation;
use App\Models\Server;
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
                        TextInput::make('allocation_ip')
                            ->datalist($this->getOwnerRecord()->node->ipAddresses())
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
                            $service->handle($this->getOwnerRecord()->node, $data, $this->getOwnerRecord());
                        } catch (Exception $exception) {
                            report($exception);

                            Notification::make()
                                ->title(str($exception::class)->afterLast('\\'))
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                AssociateAction::make()
                    ->multiple()
                    ->associateAnother(false)
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn ($query) => $query->whereBelongsTo($this->getOwnerRecord()->node)->whereNull('server_id'))
                    ->label('Add Allocation'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                ]),
            ]);
    }
}
