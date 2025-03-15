<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Helpers\Utilities;
use App\Traits\HasValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property \Carbon\Carbon|null $last_run_at
 * @property \Carbon\Carbon|null $next_run_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\Server $server
 * @property \App\Models\Task[]|\Illuminate\Support\Collection $tasks
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

    /**
     * Returns the schedule's execution crontab entry as a string.
     *
     * @throws \Exception
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
}
