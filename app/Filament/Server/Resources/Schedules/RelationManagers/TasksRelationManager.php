<?php

namespace App\Filament\Server\Resources\Schedules\RelationManagers;

use App\Extensions\Tasks\TaskService;
use App\Facades\Activity;
use App\Models\Schedule;
use App\Models\Task;
use Exception;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    /**
     * @return Component[]
     *
     * @throws Exception
     */
    private function getTaskForm(Schedule $schedule): array
    {
        /** @var TaskService $taskService */
        $taskService = app(TaskService::class); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions

        $tasks = $taskService->getAll();

        return [
            Select::make('action')
                ->label(trans('server/schedule.tasks.actions.title'))
                ->required()
                ->live()
                ->disableOptionWhen(fn (string $value) => !$tasks[$value]->canCreate($schedule))
                ->options($taskService->getMappings())
                ->selectablePlaceholder(false)
                ->default(array_key_first($tasks))
                ->afterStateUpdated(fn ($state, Set $set) => $set('payload', $tasks[$state]->getDefaultPayload())),
            Group::make(fn (Get $get) => array_key_exists($get('action'), $tasks) ? $tasks[$get('action')]->getPayloadForm() : []),
            TextInput::make('time_offset')
                ->label(trans('server/schedule.tasks.time_offset'))
                ->hidden(fn (Get $get, ?Task $task) => config('queue.default') === 'sync' || $schedule->tasks->isEmpty() || $task?->isFirst())
                ->default(0)
                ->numeric()
                ->minValue(0)
                ->maxValue(900)
                ->suffix(trans_choice('server/schedule.tasks.seconds', 2)),
            Toggle::make('continue_on_failure')
                ->label(trans('server/schedule.tasks.continue_on_failure')),
        ];
    }

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        /** @var Schedule $schedule */
        $schedule = $this->getOwnerRecord();

        return $table
            ->reorderable('sequence_id')
            ->defaultSort('sequence_id')
            ->columns([
                TextColumn::make('action')
                    ->label(trans('server/schedule.tasks.actions.title'))
                    ->state(fn (Task $task) => $task->getSchema()?->getName() ?? $task->action),
                TextColumn::make('payload')
                    ->label(trans('server/schedule.tasks.payload'))
                    ->state(fn (Task $task) => $task->getSchema()?->formatPayload($task->payload) ?? $task->payload)
                    ->tooltip(fn (Task $task) => $task->getSchema()?->getPayloadLabel())
                    ->placeholder(trans('server/schedule.tasks.no_payload'))
                    ->badge(),
                TextColumn::make('time_offset')
                    ->label(trans('server/schedule.tasks.time_offset'))
                    ->hidden(fn () => config('queue.default') === 'sync')
                    ->state(fn (Task $task) => $task->isFirst() ? null : $task->time_offset)
                    ->suffix(fn ($state) => ' ' . trans_choice('server/schedule.tasks.seconds', $state))
                    ->placeholder(trans('server/schedule.tasks.first_task')),
                IconColumn::make('continue_on_failure')
                    ->label(trans('server/schedule.tasks.continue_on_failure'))
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema($this->getTaskForm($schedule))
                    ->mutateDataUsing(function ($data) {
                        $data['payload'] ??= '';

                        return $data;
                    })
                    ->after(function ($data) {
                        /** @var Schedule $schedule */
                        $schedule = $this->getOwnerRecord();

                        Activity::event('server:task.update')
                            ->subject($schedule)
                            ->property(['name' => $schedule->name, 'action' => $data['action'], 'payload' => $data['payload']])
                            ->log();

                    }),
                DeleteAction::make()
                    ->action(function (Task $task) {
                        /** @var Schedule $schedule */
                        $schedule = $this->getOwnerRecord();
                        $task->delete();

                        Activity::event('server:task.delete')
                            ->subject($schedule)
                            ->property(['name' => $schedule->name, 'action' => $task->action, 'payload' => $task->payload])
                            ->log();
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->label(fn () => $schedule->tasks()->count() >= config('panel.client_features.schedules.per_schedule_task_limit', 10) ? trans('server/schedule.tasks.limit') : trans('server/schedule.tasks.create'))
                    ->disabled(fn () => $schedule->tasks()->count() >= config('panel.client_features.schedules.per_schedule_task_limit', 10))
                    ->schema($this->getTaskForm($schedule))
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
