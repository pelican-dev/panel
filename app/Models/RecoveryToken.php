<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property \Carbon\CarbonImmutable $created_at
 * @property \App\Models\User $user
 */
class RecoveryToken extends Model
{
    /**
     * There are no updates to this model, only inserts and deletes.
     */
    public const UPDATED_AT = null;

    public $timestamps = true;

    public static array $validationRules = [
        'token' => 'required|string',
    ];

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
