<?php

namespace App\Extensions\Tasks\Schemas;

use App\Models\Task;
use App\Repositories\Daemon\DaemonServerRepository;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Str;

final class PowerActionSchema extends TaskSchema
{
    public function __construct(private DaemonServerRepository $serverRepository) {}

    public function getId(): string
    {
        return 'power';
    }

    public function runTask(Task $task): void
    {
        $this->serverRepository->setServer($task->server)->power($task->payload);
    }

    public function getDefaultPayload(): string
    {
        return 'restart';
    }

    public function getPayloadLabel(): string
    {
        return trans('server/schedule.tasks.actions.power.action');
    }

    public function formatPayload(string $payload): string
    {
        return Str::ucfirst($payload);
    }

    /** @return Component[] */
    public function getPayloadForm(): array
    {
        return [
            Select::make('payload')
                ->label($this->getPayloadLabel())
                ->required()
                ->options([
                    'start' => trans('server/schedule.tasks.actions.power.start'),
                    'restart' => trans('server/schedule.tasks.actions.power.restart'),
                    'stop' => trans('server/schedule.tasks.actions.power.stop'),
                    'kill' => trans('server/schedule.tasks.actions.power.kill'),
                ])
                ->selectablePlaceholder(false)
                ->default($this->getDefaultPayload()),
        ];
    }
}
