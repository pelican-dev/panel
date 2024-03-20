<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'tasks_log';

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'task_id' => 'integer',
            'run_status' => 'integer',
            'run_time' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
