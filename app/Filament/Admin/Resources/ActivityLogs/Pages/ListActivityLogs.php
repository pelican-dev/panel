<?php

namespace App\Filament\Admin\Resources\ActivityLogs\Pages;

use App\Enums\TablerIcon;
use App\Filament\Admin\Resources\ActivityLogs\ActivityLogResource;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Models\ActivityLog;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    public function getHeading(): string
    {
        return trans('admin/log.navigation.admin_audit_log');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ActivityLog::query()
                    ->where('event', 'like', 'admin:%')
                    ->with(['actor'])
                    ->latest('timestamp')
            )
            ->columns([
                TextColumn::make('actor.username')
                    ->label(trans('admin/log.table.actor'))
                    ->state(function (ActivityLog $log): string {
                        if ($log->actor instanceof User) {
                            return $log->actor->username;
                        }

                        return trans('admin/log.table.system');
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $escapedSearch = addcslashes($search, '%_\\\\');

                        return $query->whereHas('actor', fn (Builder $q) => $q->where('username', 'like', "%{$escapedSearch}%"));
                    }),
                TextColumn::make('event')
                    ->label(trans('admin/log.table.event'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(trans('admin/log.table.description'))
                    ->html()
                    ->state(fn (ActivityLog $log) => new HtmlString($log->getLabel()))
                    ->grow(),
                TextColumn::make('ip')
                    ->label(trans('admin/log.table.ip'))
                    ->visibleFrom('lg')
                    ->visible(fn () => user()?->can('seeIps activityLog')),
                DateTimeColumn::make('timestamp')
                    ->label(trans('admin/log.table.timestamp'))
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('timestamp', 'desc')
            ->searchable()
            ->filters([
                SelectFilter::make('event')
                    ->label(trans('admin/log.table.event'))
                    ->options(fn () => ActivityLog::query()
                        ->where('event', 'like', 'admin:%')
                        ->distinct()
                        ->pluck('event')
                        ->mapWithKeys(fn (string $event) => [$event => $event])
                        ->toArray())
                    ->searchable(),
            ])
            ->emptyStateHeading(trans('admin/log.empty_audit_log'))
            ->emptyStateIcon(TablerIcon::ShieldSearch);
    }
}
