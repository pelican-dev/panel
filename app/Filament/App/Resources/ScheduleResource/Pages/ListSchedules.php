<?php

namespace App\Filament\App\Resources\ScheduleResource\Pages;

use App\Filament\App\Resources\ScheduleResource;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
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
                TextColumn::make('last_run_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('next_run_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
