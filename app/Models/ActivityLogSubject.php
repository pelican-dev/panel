<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * \App\Models\ActivityLogSubject.
 *
 * @property int $id
 * @property int $activity_log_id
 * @property int $subject_id
 * @property string $subject_type
 * @property ActivityLog|null $activityLog
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLogSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLogSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLogSubject query()
 */
class ActivityLogSubject extends Pivot
{
    public $incrementing = true;

    public $timestamps = false;

    protected $table = 'activity_log_subjects';

    protected $guarded = ['id'];

    public function activityLog(): BelongsTo
    {
        return $this->belongsTo(ActivityLog::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo()->withoutGlobalScope(SoftDeletingScope::class);
    }
}
