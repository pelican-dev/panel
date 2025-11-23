<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Enums\ScheduleStatus;
use App\Helpers\Utilities;
use App\Traits\HasValidation;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $server_id
 * @property string $name
 * @property string $cron_day_of_week
 * @property string $cron_month
 * @property string $cron_day_of_month
 * @property string $cron_hour
 * @property string $cron_minute
 * @property bool $is_active
 * @property bool $is_processing
 * @property bool $only_when_online
 * @property Carbon|null $last_run_at
 * @property Carbon|null $next_run_at
 * @property ScheduleStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Server $server
 * @property Task[]|Collection $tasks
 */
class Schedule extends Model implements Validatable
{
    use HasFactory;
    use HasValidation;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'server_schedule';

    /**
     * Always return the tasks associated with this schedule.
     */
    protected $with = ['tasks'];

    /**
     * Mass assignable attributes on this model.
     */
    protected $fillable = [
        'server_id',
        'name',
        'cron_day_of_week',
        'cron_month',
        'cron_day_of_month',
        'cron_hour',
        'cron_minute',
        'is_active',
        'is_processing',
        'only_when_online',
        'last_run_at',
        'next_run_at',
    ];

    protected $attributes = [
        'name' => null,
        'cron_day_of_week' => '*',
        'cron_month' => '*',
        'cron_day_of_month' => '*',
        'cron_hour' => '*',
        'cron_minute' => '*',
        'is_active' => true,
        'is_processing' => false,
        'only_when_online' => false,
    ];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'server_id' => ['required', 'exists:servers,id'],
        'name' => ['required', 'string', 'max:255'],
        'cron_day_of_week' => ['required', 'string'],
        'cron_month' => ['required', 'string'],
        'cron_day_of_month' => ['required', 'string'],
        'cron_hour' => ['required', 'string'],
        'cron_minute' => ['required', 'string'],
        'is_active' => ['boolean'],
        'is_processing' => ['boolean'],
        'only_when_online' => ['boolean'],
        'last_run_at' => ['nullable', 'date'],
        'next_run_at' => ['nullable', 'date'],
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'server_id' => 'integer',
            'is_active' => 'boolean',
            'is_processing' => 'boolean',
            'only_when_online' => 'boolean',
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
        ];
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => !$this->is_active ? ScheduleStatus::Inactive : ($this->is_processing ? ScheduleStatus::Processing : ScheduleStatus::Active),
        );
    }

    /**
     * Returns the schedule's execution crontab entry as a string.
     *
     * @throws Exception
     */
    public function getNextRunDate(): string
    {
        return Utilities::getScheduleNextRunDate($this->cron_minute, $this->cron_hour, $this->cron_day_of_month, $this->cron_month, $this->cron_day_of_week)->toDateTimeString();
    }

    /**
     * Return tasks belonging to a schedule.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Return the server model that a schedule belongs to.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function firstTask(): ?Task
    {
        /** @var ?Task $task */
        $task = $this->tasks()->orderBy('sequence_id')->first();

        return $task;
    }
}
