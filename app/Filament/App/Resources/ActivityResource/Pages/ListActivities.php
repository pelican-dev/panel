<?php

namespace App\Filament\App\Resources\ActivityResource\Pages;

use App\Filament\App\Resources\ActivityResource;
use App\Models\ActivityLog;
use App\Models\User;
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
                    ->formatStateUsing(fn ($state, ActivityLog $activityLog) => __('activity.'.str($activityLog->event)->replace(':', '.'), $activityLog->properties?->toArray() ?? []))
                    ->description(fn ($state) => $state),
                TextColumn::make('user')
                    ->state(fn (ActivityLog $activityLog) => $activityLog->actor instanceof User ? $activityLog->actor->username : 'System')
                    ->url(fn (ActivityLog $activityLog): string => $activityLog->actor instanceof User ? route('filament.admin.resources.users.edit', ['record' => $activityLog->actor]) : ''),
                TextColumn::make('timestamp')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state->diffForHumans()),
            ]);
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
