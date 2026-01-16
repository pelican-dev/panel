<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $schema
 * @property array<mixed> $configuration
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property Collection|Node[] $nodes
 * @property int|null $nodes_count
 * @property Collection|Backup[] $backups
 * @property int|null $backups_count
 */
class BackupHost extends Model implements Validatable
{
    use HasFactory;
    use HasValidation;

    public const RESOURCE_NAME = 'backup_host';

    protected $fillable = [
        'name',
        'schema',
        'configuration',
    ];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'name' => ['required', 'string', 'max:255'],
        'schema' => ['required', 'string', 'max:255'],
        'configuration' => ['nullable', 'array'],
    ];

    protected function casts(): array
    {
        return [
            'configuration' => 'array',
        ];
    }

    public function nodes(): BelongsToMany
    {
        return $this->belongsToMany(Node::class);
    }

    public function backups(): HasMany
    {
        return $this->hasMany(Backup::class, 'disk', 'schema');
    }
}
