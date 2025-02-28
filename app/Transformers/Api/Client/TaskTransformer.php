<?php

namespace App\Transformers\Api\Client;

use App\Models\Task;

class TaskTransformer extends BaseClientTransformer
{
    /**
     * {@inheritdoc}
     */
    public function getResourceName(): string
    {
        return Task::RESOURCE_NAME;
    }

    /**
     * @param  Task  $model
     */
    public function transform($model): array
    {
        return [
            'id' => $model->id,
            'sequence_id' => $model->sequence_id,
            'action' => $model->action,
            'payload' => $model->payload,
            'time_offset' => $model->time_offset,
            'is_queued' => $model->is_queued,
            'continue_on_failure' => $model->continue_on_failure,
            'created_at' => $model->created_at->toAtomString(),
            'updated_at' => $model->updated_at->toAtomString(),
        ];
    }
}
