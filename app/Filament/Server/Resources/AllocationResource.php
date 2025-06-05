<?php

namespace App\Filament\Server\Resources;

use App\Facades\Activity;
use App\Filament\Server\Resources\AllocationResource\Pages;
use App\Models\Allocation;
use App\Models\Permission;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AllocationResource extends Resource
{
    protected static ?string $model = Allocation::class;

    protected static ?string $modelLabel = 'Network';

    protected static ?string $pluralModelLabel = 'Network';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationIcon = 'tabler-network';

    public static function table(Table $table): Table
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
                            ->property('allocation', $allocation->address)
                            ->log();
                    }),
            ]);
    }

    // TODO: find better way handle server conflict state
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllocations::route('/'),
        ];
    }
}
