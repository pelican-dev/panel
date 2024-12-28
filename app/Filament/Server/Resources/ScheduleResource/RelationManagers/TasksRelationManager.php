<?php

namespace App\Filament\Server\Resources\ScheduleResource\RelationManagers;

use App\Facades\Activity;
use App\Models\Schedule;
use App\Models\Task;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    private function getActionOptions(bool $full = true): array
    {
        return [
            Task::ACTION_POWER => $full ? 'Send power action' : 'Power action',
            Task::ACTION_COMMAND => $full ? 'Send command' : 'Command',
            Task::ACTION_BACKUP => $full ? 'Create backup' : 'Files to ignore',
            Task::ACTION_DELETE_FILES => $full ? 'Delete files' : 'Files to delete',
        ];
    }

    private function getTaskForm(Schedule $schedule): array
    {
        return [
            Select::make('action')
                ->required()
                ->live()
                ->disableOptionWhen(fn (string $value): bool => $value === Task::ACTION_BACKUP && $schedule->server->backup_limit === 0)
                ->options($this->getActionOptions())
                ->selectablePlaceholder(false),
            Textarea::make('payload')
                ->hidden(fn (Get $get) => $get('action') === Task::ACTION_POWER)
                ->label(fn (Get $get) => $this->getActionOptions(false)[$get('action')] ?? 'Payload'),
            Select::make('payload')
                ->visible(fn (Get $get) => $get('action') === Task::ACTION_POWER)
                ->label('Power Action')
                ->required()
                ->options([
                    'start' => 'Start',
                    'restart' => 'Restart',
                    'stop' => 'Stop',
                    'kill' => 'Kill',
                ])
                ->selectablePlaceholder(false),
            TextInput::make('time_offset')
                ->hidden(fn (Get $get) => config('queue.default') === 'sync' || $get('sequence_id') === 1)
                ->default(0)
                ->numeric()
                ->minValue(0)
                ->maxValue(900)
                ->suffix('Seconds'),
            Toggle::make('continue_on_failure'),
        ];
    }

    public function table(Table $table): Table
    {
        /** @var Schedule $schedule */
        $schedule = $this->getOwnerRecord();

        return $table
            ->reorderable('sequence_id', true)
            ->columns([
                TextColumn::make('action')
                    ->state(fn (Task $task) => $this->getActionOptions()[$task->action] ?? $task->action),
                TextColumn::make('payload')
                    ->state(function (Task $task) {
                        $payload = match ($task->payload) {
                            'start', 'restart', 'stop', 'kill' => mb_ucfirst($task->payload),
                            default => $task->payload
                        };

                        return explode(PHP_EOL, $payload);
                    })
                    ->badge(),
                TextColumn::make('time_offset')
                    ->hidden(fn () => config('queue.default') === 'sync')
                    ->suffix(' Seconds'),
                IconColumn::make('continue_on_failure')
                    ->boolean(),
            ])
            ->actions([
                EditAction::make()
                    ->form($this->getTaskForm($schedule)),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->label(fn () => $schedule->tasks()->count() >= config('panel.client_features.schedules.per_schedule_task_limit', 10) ? 'Task Limit Reached' : 'Create Task')
                    ->disabled(fn () => $schedule->tasks()->count() >= config('panel.client_features.schedules.per_schedule_task_limit', 10))
                    ->form($this->getTaskForm($schedule))
                    ->action(function ($data) use ($schedule) {
                        $sequenceId = ($schedule->tasks()->orderByDesc('sequence_id')->first()->sequence_id ?? 0) + 1;

                        $task = Task::query()->create([
                            'schedule_id' => $schedule->id,
                            'sequence_id' => $sequenceId,
                            'action' => $data['action'],
                            'payload' => $data['payload'] ?? '',
                            'time_offset' => $data['time_offset'] ?? 0,
                            'continue_on_failure' => (bool) $data['continue_on_failure'],
                        ]);

                        Activity::event('server:task.create')
                            ->subject($schedule, $task)
                            ->property(['name' => $schedule->name, 'action' => $task->action, 'payload' => $task->payload])
                            ->log();
                    }),
            ]);
    }
}
