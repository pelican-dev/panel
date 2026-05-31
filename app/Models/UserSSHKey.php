<?php

namespace App\Models;

use App\Traits\HasValidation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * \App\Models\UserSSHKey.
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $fingerprint
 * @property string $public_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $user
 *
 * @method static \Database\Factories\UserSSHKeyFactory factory($count = null, $state = [])
 * @method static Builder<static>|UserSSHKey newModelQuery()
 * @method static Builder<static>|UserSSHKey newQuery()
 * @method static Builder<static>|UserSSHKey onlyTrashed()
 * @method static Builder<static>|UserSSHKey query()
 * @method static Builder<static>|UserSSHKey whereCreatedAt($value)
 * @method static Builder<static>|UserSSHKey whereDeletedAt($value)
 * @method static Builder<static>|UserSSHKey whereFingerprint($value)
 * @method static Builder<static>|UserSSHKey whereId($value)
 * @method static Builder<static>|UserSSHKey whereName($value)
 * @method static Builder<static>|UserSSHKey wherePublicKey($value)
 * @method static Builder<static>|UserSSHKey whereUpdatedAt($value)
 * @method static Builder<static>|UserSSHKey whereUserId($value)
 * @method static Builder<static>|UserSSHKey withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|UserSSHKey withoutTrashed()
 */
class UserSSHKey extends Model
{
    use HasFactory;
    use HasValidation;
    use SoftDeletes;

    public const RESOURCE_NAME = 'ssh_key';

    protected $table = 'user_ssh_keys';

    protected $fillable = [
        'name',
        'public_key',
        'fingerprint',
    ];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'name' => ['required', 'string'],
        'fingerprint' => ['required', 'string'],
        'public_key' => ['required', 'string'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
