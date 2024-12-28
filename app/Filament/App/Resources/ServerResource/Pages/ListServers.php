<?php

namespace App\Filament\App\Resources\ServerResource\Pages;

use App\Filament\App\Resources\ServerResource;
use App\Filament\Components\Tables\Columns\ServerEntryColumn;
use App\Filament\Server\Pages\Console;
use App\Models\Server;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                'md' => 2,
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
}
