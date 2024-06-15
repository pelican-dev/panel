<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * \App\Models\ActivityLogSubject.
 *
 * @property int $id
 * @property int $activity_log_id
 * @property int $subject_id
 * @property string $subject_type
 * @property \App\Models\ActivityLog|null $activityLog
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLogSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLogSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLogSubject query()
 *
 * @mixin \Eloquent
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

    public function subject()
    {
        $morph = $this->morphTo();

        if (in_array(SoftDeletes::class, class_uses_recursive($morph::class))) {
            /** @var self|Backup|UserSSHKey $morph - cannot use traits in doc blocks */
            return $morph->withTrashed();
        }

        return $morph;
    }
}
