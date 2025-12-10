<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Extensions\Tasks\TaskSchemaInterface;
use App\Extensions\Tasks\TaskService;
use App\Traits\HasValidation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property int $id
 * @property int $schedule_id
 * @property int $sequence_id
 * @property string $action
 * @property string $payload
 * @property int $time_offset
 * @property bool $is_queued
 * @property bool $continue_on_failure
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Schedule $schedule
 * @property Server $server
 */
class Task extends Model implements Validatable
{
    use HasFactory;
    use HasValidation;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'schedule_task';

    /**
     * Relationships to be updated when this model is updated.
     *
     * @var string[]
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

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'schedule_id' => ['required', 'numeric', 'exists:schedules,id'],
        'sequence_id' => ['required', 'numeric', 'min:1'],
        'action' => ['required', 'string'],
        'payload' => ['required_unless:action,backup', 'string'],
        'time_offset' => ['required', 'numeric', 'between:0,900'],
        'is_queued' => ['boolean'],
        'continue_on_failure' => ['boolean'],
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

    public function isFirst(): bool
    {
        return $this->schedule->firstTask()?->id === $this->id;
    }

    public function getSchema(): ?TaskSchemaInterface
    {
        /** @var TaskService $taskService */
        $taskService = app(TaskService::class); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions

        return $taskService->get($this->action);
    }
}
