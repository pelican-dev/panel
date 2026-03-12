<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Eloquent\BackupQueryBuilder;
use App\Enums\BackupStatus;
use App\Traits\HasValidation;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * @property int $id
 * @property int $server_id
 * @property string $uuid
 * @property string $name
 * @property array<array-key, mixed> $ignored_files
 * @property string $disk
 * @property string|null $checksum
 * @property int $bytes
 * @property CarbonImmutable|null $completed_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property bool $is_successful
 * @property string|null $upload_id
 * @property bool $is_locked
 * @property-read Server $server
 * @property-read BackupStatus $status
 *
 * @method static \Database\Factories\BackupFactory factory($count = null, $state = [])
 * @method static BackupQueryBuilder<static>|Backup newModelQuery()
 * @method static BackupQueryBuilder<static>|Backup newQuery()
 * @method static BackupQueryBuilder<static>|Backup nonFailed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup onlyTrashed()
 * @method static BackupQueryBuilder<static>|Backup query()
 * @method static BackupQueryBuilder<static>|Backup whereBytes($value)
 * @method static BackupQueryBuilder<static>|Backup whereChecksum($value)
 * @method static BackupQueryBuilder<static>|Backup whereCompletedAt($value)
 * @method static BackupQueryBuilder<static>|Backup whereCreatedAt($value)
 * @method static BackupQueryBuilder<static>|Backup whereDeletedAt($value)
 * @method static BackupQueryBuilder<static>|Backup whereDisk($value)
 * @method static BackupQueryBuilder<static>|Backup whereId($value)
 * @method static BackupQueryBuilder<static>|Backup whereIgnoredFiles($value)
 * @method static BackupQueryBuilder<static>|Backup whereIsLocked($value)
 * @method static BackupQueryBuilder<static>|Backup whereIsSuccessful($value)
 * @method static BackupQueryBuilder<static>|Backup whereName($value)
 * @method static BackupQueryBuilder<static>|Backup whereServerId($value)
 * @method static BackupQueryBuilder<static>|Backup whereUpdatedAt($value)
 * @method static BackupQueryBuilder<static>|Backup whereUploadId($value)
 * @method static BackupQueryBuilder<static>|Backup whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup withoutTrashed()
 */
class Backup extends Model implements Validatable
{
    use HasFactory;
    use HasValidation;
    use SoftDeletes;

    public const RESOURCE_NAME = 'backup';

    public const ADAPTER_DAEMON = 'wings';

    public const ADAPTER_AWS_S3 = 's3';

    protected $attributes = [
        'is_successful' => false,
        'is_locked' => false,
        'checksum' => null,
        'bytes' => 0,
        'upload_id' => null,
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'server_id' => ['bail', 'required', 'numeric', 'exists:servers,id'],
        'uuid' => ['required', 'uuid'],
        'is_successful' => ['boolean'],
        'is_locked' => ['boolean'],
        'name' => ['required', 'string'],
        'ignored_files' => ['array'],
        'disk' => ['required', 'string'],
        'checksum' => ['nullable', 'string'],
        'bytes' => ['numeric'],
        'upload_id' => ['nullable', 'string'],
    ];

    protected function casts(): array
    {
        return [
            'id' => 'int',
            'is_successful' => 'bool',
            'is_locked' => 'bool',
            'ignored_files' => 'array',
            'bytes' => 'int',
            'completed_at' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
            'deleted_at' => 'immutable_datetime',
        ];
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => !$this->completed_at ? BackupStatus::InProgress : ($this->is_successful ? BackupStatus::Successful : BackupStatus::Failed),
        );
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * @param  Builder  $query
     * @return BackupQueryBuilder<Model>
     */
    public function newEloquentBuilder($query): BackupQueryBuilder
    {
        return new BackupQueryBuilder($query);
    }
}
