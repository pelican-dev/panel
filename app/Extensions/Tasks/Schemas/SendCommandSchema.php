<?php

namespace App\Extensions\Tasks\Schemas;

use App\Models\Task;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

final class SendCommandSchema extends TaskSchema
{
    public function getId(): string
    {
        return 'command';
    }

    public function runTask(Task $task): void
    {
        $task->server->send($task->payload);
    }

    public function getPayloadLabel(): string
    {
        return trans('server/schedule.tasks.actions.command.command');
    }

    /** @return Component[] */
    public function getPayloadForm(): array
    {
        return [
            TextInput::make('payload')
                ->required()
                ->label($this->getPayloadLabel())
                ->default($this->getDefaultPayload()),
        ];
    }
}
