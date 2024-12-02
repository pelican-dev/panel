<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Filament\Server\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Tables\Columns\DateTimeColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('cron')
                    ->state(fn (Schedule $schedule) => $schedule->cron_minute . ' ' . $schedule->cron_hour . ' ' . $schedule->cron_day_of_month . ' ' . $schedule->cron_month . ' ' . $schedule->cron_day_of_week),
                TextColumn::make('status')
                    ->state(fn (Schedule $schedule) => !$schedule->is_active ? 'Inactive' : ($schedule->is_processing ? 'Processing' : 'Active')),
                IconColumn::make('only_when_online')
                    ->boolean()
                    ->sortable(),
                DateTimeColumn::make('last_run_at')
                    ->label('Last run')
                    ->placeholder('Never')
                    ->since()
                    ->sortable(),
                DateTimeColumn::make('next_run_at')
                    ->label('Next run')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
