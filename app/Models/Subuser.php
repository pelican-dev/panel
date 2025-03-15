<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $server_id
 * @property string[] $permissions
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\User $user
 * @property \App\Models\Server $server
 */
class Subuser extends Model implements Validatable
{
    use HasFactory;
    use HasValidation;
    use Notifiable;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'server_subuser';

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'user_id' => ['required', 'numeric', 'exists:users,id'],
        'server_id' => ['required', 'numeric', 'exists:servers,id'],
        'permissions' => ['nullable', 'array'],
        'permissions.*' => ['string'],
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'int',
            'server_id' => 'int',
            'permissions' => 'array',
        ];
    }

    /**
     * Gets the server associated with a subuser.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Gets the user associated with a subuser.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gets the permissions associated with a subuser.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
