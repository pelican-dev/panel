<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property CarbonImmutable $created_at
 * @property User $user
 */
class RecoveryToken extends Model implements Validatable
{
    use HasValidation;

    /**
     * There are no updates to this model, only creates and deletes.
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
