<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $schedule_id
 * @property int $sequence_id
 * @property string $action
 * @property string $payload
 * @property int $time_offset
 * @property bool $is_queued
 * @property bool $continue_on_failure
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\Schedule $schedule
 * @property \App\Models\Server $server
 */
class Task extends Model
{
    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'schedule_task';

    /**
     * The default actions that can exist for a task
     */
    public const ACTION_POWER = 'power';
    public const ACTION_COMMAND = 'command';
    public const ACTION_BACKUP = 'backup';
    public const ACTION_DELETE_FILES = 'delete_files';

    /**
     * The table associated with the model.
     */
    protected $table = 'tasks';

    /**
     * Relationships to be updated when this model is updated.
     */
    protected $touches = ['schedule'];

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'schedule_id',
        'sequence_id',
        'action',
        'payload',
        'time_offset',
        'is_queued',
        'continue_on_failure',
    ];

    /**
     * Default attributes when creating a new model.
     */
    protected $attributes = [
        'time_offset' => 0,
        'is_queued' => false,
        'continue_on_failure' => false,
    ];

    public static array $validationRules = [
        'schedule_id' => 'required|numeric|exists:schedules,id',
        'sequence_id' => 'required|numeric|min:1',
        'action' => 'required|string',
        'payload' => 'required_unless:action,backup|string',
        'time_offset' => 'required|numeric|between:0,900',
        'is_queued' => 'boolean',
        'continue_on_failure' => 'boolean',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'schedule_id' => 'integer',
            'sequence_id' => 'integer',
            'time_offset' => 'integer',
            'is_queued' => 'boolean',
            'continue_on_failure' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return $this->getKeyName();
    }

    /**
     * Return the schedule that a task belongs to.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Return the server a task is assigned to, acts as a belongsToThrough.
     */
    public function server(): HasOneThrough
    {
        return $this->hasOneThrough(
            Server::class,
            Schedule::class,
            'id', // schedules.id
            'id', // servers.id
            'schedule_id', // tasks.schedule_id
            'server_id' // schedules.server_id
        );
    }
}
