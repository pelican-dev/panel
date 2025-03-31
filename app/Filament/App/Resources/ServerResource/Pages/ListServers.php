<?php

namespace App\Filament\App\Resources\ServerResource\Pages;

use App\Enums\ServerResourceType;
use App\Filament\App\Resources\ServerResource;
use App\Filament\Server\Pages\Console;
use App\Models\Server;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListServers extends ListRecords
{
    protected static string $resource = ServerResource::class;

    public function table(Table $table): Table
    {
        $baseQuery = auth()->user()->accessibleServers();

        return $table
            ->paginated(false)
            ->query(fn () => $baseQuery)
            ->poll('15s')
            ->columns([
                TextColumn::make('condition')
                    ->label('Status')
                    ->default('unknown')
                    ->wrap()
                    ->badge()
                    ->alignCenter()
                    ->icon(fn (Server $server) => $server->condition->getIcon())
                    ->color(fn (Server $server) => $server->condition->getColor()),
                TextColumn::make('uptime')
                    ->label('')
                    ->icon('tabler-clock')
                    ->state(fn (Server $server) => $server->formatResource('uptime', type: ServerResourceType::Time)),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('')
                    ->label('Network')
                    ->icon('tabler-network')
                    ->state(fn (Server $server) => $server->allocation->address),
                TextColumn::make('cpuUsage')
                    ->label('CPU')
                    ->icon('tabler-cpu')
                    ->tooltip(fn (Server $server) => 'Usage Limit: ' . $server->formatResource('cpu', limit: true, type: ServerResourceType::Percentage, precision: 0))
                    ->state(fn (Server $server) => $server->formatResource('cpu_absolute', type: ServerResourceType::Percentage)),
                TextColumn::make('memoryUsage')
                    ->label('Memory')
                    ->icon('tabler-memory')
                    ->tooltip(fn (Server $server) => 'Usage Limit: ' . $server->formatResource('memory', limit: true))
                    ->state(fn (Server $server) => $server->formatResource('memory_bytes')),
                TextColumn::make('diskUsage')
                    ->label('Disk')
                    ->icon('tabler-device-floppy')
                    ->tooltip(fn (Server $server) => 'Usage Limit: ' . $server->formatResource('disk', limit: true))
                    ->state(fn (Server $server) => $server->formatResource('disk_bytes')),
            ])
            ->recordUrl(fn (Server $server) => Console::getUrl(panel: 'server', tenant: $server))
            ->emptyStateIcon('tabler-brand-docker')
            ->emptyStateDescription('')
            ->emptyStateHeading(fn () => $this->activeTab === 'my' ? 'You don\'t own any servers!' : 'You don\'t have access to any servers!')
            ->persistFiltersInSession()
            ->filters([
                SelectFilter::make('egg')
                    ->relationship('egg', 'name', fn (Builder $query) => $query->whereIn('id', $baseQuery->pluck('egg_id')))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('owner')
                    ->relationship('user', 'username', fn (Builder $query) => $query->whereIn('id', $baseQuery->pluck('owner_id')))
                    ->searchable()
                    ->hidden(fn () => $this->activeTab === 'my')
                    ->preload(),
            ]);
    }

    public function updatedActiveTab(): void
    {
        $this->resetTable();
    }

    public function getTabs(): array
    {
        $all = auth()->user()->accessibleServers();
        $my = (clone $all)->where('owner_id', auth()->user()->id);
        $other = (clone $all)->whereNot('owner_id', auth()->user()->id);

        return [
            'my' => Tab::make('My Servers')
                ->badge(fn () => $my->count())
                ->modifyQueryUsing(fn () => $my),

            'other' => Tab::make('Others\' Servers')
                ->badge(fn () => $other->count())
                ->modifyQueryUsing(fn () => $other),

            'all' => Tab::make('All Servers')
                ->badge($all->count()),
        ];
    }
}
