<?php

namespace App\Filament\Server\Resources\ActivityResource\Pages;

use App\Filament\Server\Resources\ActivityResource;
use App\Models\ActivityLog;
use App\Models\User;
use App\Tables\Columns\DateTimeColumn;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event')
                    ->html()
                    ->formatStateUsing(fn ($state, ActivityLog $activityLog) => __('activity.'.str($state)->replace(':', '.'), $activityLog->properties?->toArray() ?? []))
                    ->description(fn ($state) => $state),
                TextColumn::make('user')
                    ->state(fn (ActivityLog $activityLog) => $activityLog->actor instanceof User ? $activityLog->actor->username : 'System')
                    ->url(fn (ActivityLog $activityLog): string => $activityLog->actor instanceof User ? route('filament.admin.resources.users.edit', ['record' => $activityLog->actor]) : ''),
                DateTimeColumn::make('timestamp')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('timestamp', 'desc');
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
