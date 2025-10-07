<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $server_id
 * @property int $old_node
 * @property int $new_node
 * @property int|null $old_allocation
 * @property int|null $new_allocation
 * @property array<int>|null $old_additional_allocations array of allocation.id's
 * @property array<int>|null $new_additional_allocations array of allocation.id's
 * @property bool|null $successful
 * @property bool $archived
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Server $server
 * @property Node $oldNode
 * @property Node $newNode
 */
class ServerTransfer extends Model implements Validatable
{
    use HasValidation;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'server_transfer';

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'server_id' => ['required', 'numeric', 'exists:servers,id'],
        'old_node' => ['required', 'numeric'],
        'new_node' => ['required', 'numeric'],
        'old_allocation' => ['nullable', 'numeric'],
        'new_allocation' => ['nullable', 'numeric'],
        'old_additional_allocations' => ['nullable', 'array'],
        'old_additional_allocations.*' => ['numeric'],
        'new_additional_allocations' => ['nullable', 'array'],
        'new_additional_allocations.*' => ['numeric'],
        'successful' => ['sometimes', 'nullable', 'boolean'],
    ];

    protected function casts(): array
    {
        return [
            'server_id' => 'int',
            'old_node' => 'int',
            'new_node' => 'int',
            'old_allocation' => 'int',
            'new_allocation' => 'int',
            'old_additional_allocations' => 'array',
            'new_additional_allocations' => 'array',
            'successful' => 'bool',
            'archived' => 'bool',
        ];
    }

    /**
     * Gets the server associated with a server transfer.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Gets the source node associated with a server transfer.
     */
    public function oldNode(): HasOne
    {
        return $this->hasOne(Node::class, 'id', 'old_node');
    }

    /**
     * Gets the target node associated with a server transfer.
     */
    public function newNode(): HasOne
    {
        return $this->hasOne(Node::class, 'id', 'new_node');
    }
}
