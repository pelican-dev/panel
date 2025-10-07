<?php

namespace App\Models;

use App\Traits\HasValidation;
use Database\Factories\UserSSHKeyFactory;
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
 * @property User $user
 *
 * @method static Builder|UserSSHKey newModelQuery()
 * @method static Builder|UserSSHKey newQuery()
 * @method static \Illuminate\Database\Query\Builder|UserSSHKey onlyTrashed()
 * @method static Builder|UserSSHKey query()
 * @method static Builder|UserSSHKey whereCreatedAt($value)
 * @method static Builder|UserSSHKey whereDeletedAt($value)
 * @method static Builder|UserSSHKey whereFingerprint($value)
 * @method static Builder|UserSSHKey whereId($value)
 * @method static Builder|UserSSHKey whereName($value)
 * @method static Builder|UserSSHKey wherePublicKey($value)
 * @method static Builder|UserSSHKey whereUpdatedAt($value)
 * @method static Builder|UserSSHKey whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|UserSSHKey withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserSSHKey withoutTrashed()
 * @method static UserSSHKeyFactory factory(...$parameters)
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
