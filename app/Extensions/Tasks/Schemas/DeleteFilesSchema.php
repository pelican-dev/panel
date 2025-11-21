<?php

namespace App\Extensions\Tasks\Schemas;

use App\Models\Task;
use App\Services\Files\DeleteFilesService;

final class DeleteFilesSchema extends TaskSchema
{
    public function __construct(private DeleteFilesService $deleteFilesService) {}

    public function getId(): string
    {
        return 'delete_files';
    }

    public function runTask(Task $task): void
    {
        $this->deleteFilesService->handle($task->server, explode(PHP_EOL, $task->payload));
    }

    public function getPayloadLabel(): string
    {
        return trans('server/schedule.tasks.actions.delete_files.files_to_delete');
    }
}
