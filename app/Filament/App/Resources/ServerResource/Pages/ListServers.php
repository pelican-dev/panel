<?php

namespace App\Filament\App\Resources\ServerResource\Pages;

use App\Filament\App\Resources\ServerResource;
use App\Filament\Server\Pages\Console;
use App\Models\Server;
use App\Tables\Columns\ServerEntryColumn;
use Carbon\CarbonInterface;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Number;

class ListServers extends ListRecords
{
    protected static string $resource = ServerResource::class;

    public function table(Table $table): Table
    {
        $baseQuery = auth()->user()->can('viewList server') ? Server::query() : auth()->user()->accessibleServers();

        return $table
            ->paginated(false)
            ->query(fn () => $baseQuery)
            ->poll('15s')
            ->columns([
                Stack::make([
                    ServerEntryColumn::make('server_entry')
                        ->searchable(['name']),
                ]),
            ])
            ->contentGrid([
                'default' => 1,
                'xl' => 2,
            ])
            ->recordUrl(fn (Server $server) => Console::getUrl(panel: 'server', tenant: $server))
            ->emptyStateIcon('tabler-brand-docker')
            ->emptyStateDescription('')
            ->emptyStateHeading('You don\'t have access to any servers!')
            ->persistFiltersInSession()
            ->filters([
                TernaryFilter::make('only_my_servers')
                    ->label('Owned by')
                    ->placeholder('All servers')
                    ->trueLabel('My Servers')
                    ->falseLabel('Others\' Servers')
                    ->default()
                    ->queries(
                        true: fn (Builder $query) => $query->where('owner_id', auth()->user()->id),
                        false: fn (Builder $query) => $query->whereNot('owner_id', auth()->user()->id),
                        blank: fn (Builder $query) => $query,
                    ),
                SelectFilter::make('egg')
                    ->relationship('egg', 'name', fn (Builder $query) => $query->whereIn('id', $baseQuery->pluck('egg_id')))
                    ->searchable()
                    ->preload(),
            ]);
    }

    // @phpstan-ignore-next-line
    private function uptime(Server $server): string
    {
        $uptime = Arr::get($server->resources(), 'uptime', 0);

        if ($uptime === 0) {
            return 'Offline';
        }

        return now()->subMillis($uptime)->diffForHumans(syntax: CarbonInterface::DIFF_ABSOLUTE, short: true, parts: 2);
    }

    // @phpstan-ignore-next-line
    private function cpu(Server $server): string
    {
        $cpu = Number::format(Arr::get($server->resources(), 'cpu_absolute', 0), maxPrecision: 2, locale: auth()->user()->language) . '%';
        $max = Number::format($server->cpu, locale: auth()->user()->language) . '%';

        return $cpu . ($server->cpu > 0 ? ' Of ' . $max : '');
    }

    // @phpstan-ignore-next-line
    private function memory(Server $server): string
    {
        $latestMemoryUsed = Arr::get($server->resources(), 'memory_bytes', 0);
        $totalMemory = Arr::get($server->resources(), 'memory_limit_bytes', 0);

        $used = config('panel.use_binary_prefix')
            ? Number::format($latestMemoryUsed / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($latestMemoryUsed / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        if ($totalMemory === 0) {
            $total = config('panel.use_binary_prefix')
                ? Number::format($server->memory / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
                : Number::format($server->memory / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';
        } else {
            $total = config('panel.use_binary_prefix')
                ? Number::format($totalMemory / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
                : Number::format($totalMemory / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';
        }

        return $used . ($server->memory > 0 ? ' Of ' . $total : '');
    }

    // @phpstan-ignore-next-line
    private function disk(Server $server): string
    {
        $usedDisk = Arr::get($server->resources(), 'disk_bytes', 0);

        $used = config('panel.use_binary_prefix')
            ? Number::format($usedDisk / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($usedDisk / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        $total = config('panel.use_binary_prefix')
            ? Number::format($server->disk / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($server->disk / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        return $used . ($server->disk > 0 ? ' Of ' . $total : '');
    }
}
