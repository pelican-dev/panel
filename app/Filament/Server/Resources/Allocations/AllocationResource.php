<?php

namespace App\Filament\Server\Resources\Allocations;

use App\Facades\Activity;
use App\Filament\Server\Resources\Allocations\Pages\ListAllocations;
use App\Models\Allocation;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Allocations\FindAssignableAllocationService;
use App\Traits\Filament\BlockAccessInConflict;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyTable;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\DetachAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AllocationResource extends Resource
{
    use BlockAccessInConflict;
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyTable;

    protected static ?string $model = Allocation::class;

    protected static ?int $navigationSort = 7;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-network';

    /**
     * @throws Exception
     */
    public static function defaultTable(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $table
            ->columns([
                TextColumn::make('ip')
                    ->label(trans('server/network.address'))
                    ->formatStateUsing(fn (Allocation $allocation) => $allocation->alias),
                TextColumn::make('alias')
                    ->hidden(),
                TextColumn::make('port')
                    ->label(trans('server/network.port')),
                TextInputColumn::make('notes')
                    ->label(trans('server/network.notes'))
                    ->visibleFrom('sm')
                    ->disabled(fn () => !auth()->user()->can(Permission::ACTION_ALLOCATION_UPDATE, $server))
                    ->placeholder(trans('server/network.no_notes')),
                IconColumn::make('primary')
                    ->icon(fn ($state) => match ($state) {
                        true => 'tabler-star-filled',
                        default => 'tabler-star',
                    })
                    ->color(fn ($state) => match ($state) {
                        true => 'warning',
                        default => 'gray',
                    })
                    ->tooltip(fn (Allocation $allocation) => $allocation->id === $server->allocation_id ? trans('server/network.primary') : trans('server/network.make_primary'))
                    ->action(fn (Allocation $allocation) => auth()->user()->can(PERMISSION::ACTION_ALLOCATION_UPDATE, $server) && $server->update(['allocation_id' => $allocation->id]))
                    ->default(fn (Allocation $allocation) => $allocation->id === $server->allocation_id)
                    ->label(trans('server/network.primary')),
            ])
            ->recordActions([
                DetachAction::make()
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_ALLOCATION_DELETE, $server))
                    ->label(trans('server/network.delete'))
                    ->icon('tabler-trash')
                    ->action(function (Allocation $allocation) {
                        Allocation::query()->where('id', $allocation->id)->update([
                            'notes' => null,
                            'server_id' => null,
                        ]);

                        Activity::event('server:allocation.delete')
                            ->subject($allocation)
                            ->property('allocation', $allocation->address)
                            ->log();
                    })
                    ->after(fn (Allocation $allocation) => $allocation->id === $server->allocation_id && $server->update(['allocation_id' => $server->allocations()->first()?->id])),
            ])
            ->toolbarActions([
                Action::make('addAllocation')
                    ->hiddenLabel()->iconButton()->iconSize(IconSize::ExtraLarge)
                    ->icon(fn () => $server->allocations()->count() >= $server->allocation_limit ? 'tabler-network-off' : 'tabler-network')
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_ALLOCATION_CREATE, $server))
                    ->tooltip(fn () => $server->allocations()->count() >= $server->allocation_limit ? trans('server/network.limit') : trans('server/network.add'))
                    ->hidden(fn () => !config('panel.client_features.allocations.enabled'))
                    ->disabled(fn () => $server->allocations()->count() >= $server->allocation_limit)
                    ->color(fn () => $server->allocations()->count() >= $server->allocation_limit ? 'danger' : 'primary')
                    ->action(function (FindAssignableAllocationService $service) use ($server) {
                        $allocation = $service->handle($server);

                        if (!$server->allocation_id) {
                            $server->update(['allocation_id' => $allocation->id]);
                        }

                        Activity::event('server:allocation.create')
                            ->subject($allocation)
                            ->property('allocation', $allocation->address)
                            ->log();
                    }),
            ]);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_ALLOCATION_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_ALLOCATION_CREATE, Filament::getTenant());
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_ALLOCATION_UPDATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_ALLOCATION_DELETE, Filament::getTenant());
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListAllocations::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/network.title');
    }
}
