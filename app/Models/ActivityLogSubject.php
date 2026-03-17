<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletingScope;

/**
 * \App\Models\ActivityLogSubject.
 *
 * @property int $id
 * @property int $activity_log_id
 * @property string $subject_type
 * @property int $subject_id
 * @property-read ActivityLog $activityLog
 * @property-read Model|\Eloquent $subject
 *
 * @method static Builder<static>|ActivityLogSubject newModelQuery()
 * @method static Builder<static>|ActivityLogSubject newQuery()
 * @method static Builder<static>|ActivityLogSubject query()
 * @method static Builder<static>|ActivityLogSubject whereActivityLogId($value)
 * @method static Builder<static>|ActivityLogSubject whereId($value)
 * @method static Builder<static>|ActivityLogSubject whereSubjectId($value)
 * @method static Builder<static>|ActivityLogSubject whereSubjectType($value)
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
