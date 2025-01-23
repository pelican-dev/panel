<?php

namespace App\Filament\Server\Resources\ActivityResource\Pages;

use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Server\Resources\ActivityResource;
use App\Models\ActivityLog;
use App\Models\User;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
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
                    ->description(fn ($state) => $state)
                    ->formatStateUsing(function ($state, ActivityLog $activityLog) {
                        $properties = $activityLog->wrapProperties();

                        return trans_choice('activity.'.str($state)->replace(':', '.'), array_get($properties, 'count', 1), $properties);
                    })
                    ->tooltip(fn (ActivityLog $activityLog) => implode(',', array_get($activityLog->properties, 'files', []))),
                TextColumn::make('user')
                    ->state(fn (ActivityLog $activityLog) => $activityLog->actor instanceof User ? $activityLog->actor->username : 'System')
                    ->tooltip(fn (ActivityLog $activityLog) => auth()->user()->can('seeIps activityLog') ? $activityLog->ip : '')
                    ->url(fn (ActivityLog $activityLog): string => $activityLog->actor instanceof User ? EditUser::getUrl(['record' => $activityLog->actor], panel: 'admin', tenant: null) : ''),
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
