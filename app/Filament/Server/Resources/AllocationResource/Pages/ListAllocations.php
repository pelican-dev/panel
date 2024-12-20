<?php

namespace App\Filament\Server\Resources\AllocationResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\AllocationResource;
use App\Models\Allocation;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Allocations\FindAssignableAllocationService;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;

class ListAllocations extends ListRecords
{
    protected static string $resource = AllocationResource::class;

    public function table(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $table
            ->columns([
                TextColumn::make('ip')
                    ->label('Address')
                    ->formatStateUsing(fn (Allocation $allocation) => $allocation->alias),
                TextColumn::make('alias')
                    ->hidden(),
                TextColumn::make('port'),
                TextInputColumn::make('notes')
                    ->visibleFrom('sm')
                    ->disabled(fn () => !auth()->user()->can(Permission::ACTION_ALLOCATION_UPDATE, $server))
                    ->label('Notes')
                    ->placeholder('No Notes'),
                IconColumn::make('primary')
                    ->icon(fn ($state) => match ($state) {
                        true => 'tabler-star-filled',
                        default => 'tabler-star',
                    })
                    ->color(fn ($state) => match ($state) {
                        true => 'warning',
                        default => 'gray',
                    })
                    ->action(function (Allocation $allocation) use ($server) {
                        if (auth()->user()->can(PERMISSION::ACTION_ALLOCATION_UPDATE, $server)) {
                            return $server->update(['allocation_id' => $allocation->id]);
                        }
                    })
                    ->default(fn (Allocation $allocation) => $allocation->id === $server->allocation_id)
                    ->label('Primary'),
            ])
            ->actions([
                DetachAction::make()
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_ALLOCATION_DELETE, $server))
                    ->label('Delete')
                    ->icon('tabler-trash')
                    ->hidden(fn (Allocation $allocation) => $allocation->id === $server->allocation_id)
                    ->action(function (Allocation $allocation) {
                        Allocation::query()->where('id', $allocation->id)->update([
                            'notes' => null,
                            'server_id' => null,
                        ]);

                        Activity::event('server:allocation.delete')
                            ->subject($allocation)
                            ->property('allocation', $allocation->toString())
                            ->log();
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            Actions\Action::make('addAllocation')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_ALLOCATION_CREATE, $server))
                ->label(fn () => $server->allocations()->count() >= $server->allocation_limit ? 'Allocation limit reached' : 'Add Allocation')
                ->hidden(fn () => !config('panel.client_features.allocations.enabled'))
                ->disabled(fn () => $server->allocations()->count() >= $server->allocation_limit)
                ->color(fn () => $server->allocations()->count() >= $server->allocation_limit ? 'danger' : 'primary')
                ->action(function (FindAssignableAllocationService $service) use ($server) {
                    $allocation = $service->handle($server);

                    Activity::event('server:allocation.create')
                        ->subject($allocation)
                        ->property('allocation', $allocation->toString())
                        ->log();
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
