<?php

namespace App\Filament\Server\Resources\AllocationResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\AllocationResource;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Allocations\FindAssignableAllocationService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListAllocations extends ListRecords
{
    protected static string $resource = AllocationResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            Action::make('addAllocation')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_ALLOCATION_CREATE, $server))
                ->label(fn () => $server->allocations()->count() >= $server->allocation_limit ? 'Allocation limit reached' : 'Add Allocation')
                ->hidden(fn () => !config('panel.client_features.allocations.enabled'))
                ->disabled(fn () => $server->allocations()->count() >= $server->allocation_limit)
                ->color(fn () => $server->allocations()->count() >= $server->allocation_limit ? 'danger' : 'primary')
                ->action(function (FindAssignableAllocationService $service) use ($server) {
                    $allocation = $service->handle($server);

                    Activity::event('server:allocation.create')
                        ->subject($allocation)
                        ->property('allocation', $allocation->address)
                        ->log();
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
