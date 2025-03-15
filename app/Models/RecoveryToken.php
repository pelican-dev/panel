<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property \Carbon\CarbonImmutable $created_at
 * @property \App\Models\User $user
 */
class RecoveryToken extends Model implements Validatable
{
    use HasValidation;

    /**
     * There are no updates to this model, only inserts and deletes.
     */
    public const UPDATED_AT = null;

    public $timestamps = true;

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'token' => ['required', 'string'],
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'immutable_datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
