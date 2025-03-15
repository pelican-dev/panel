<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Eloquent\BackupQueryBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $server_id
 * @property string $uuid
 * @property bool $is_successful
 * @property bool $is_locked
 * @property string $name
 * @property string[] $ignored_files
 * @property string $disk
 * @property string|null $checksum
 * @property int $bytes
 * @property string|null $upload_id
 * @property \Carbon\CarbonImmutable|null $completed_at
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property \App\Models\Server $server
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

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return BackupQueryBuilder<\Illuminate\Database\Eloquent\Model>
     */
    public function newEloquentBuilder($query): BackupQueryBuilder
    {
        return new BackupQueryBuilder($query);
    }
}
