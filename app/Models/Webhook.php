<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $webhook_configuration_id
 * @property string $event
 * @property string $endpoint
 * @property Carbon|null $successful_at
 * @property array<array-key, mixed> $payload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<static>|Webhook newModelQuery()
 * @method static Builder<static>|Webhook newQuery()
 * @method static Builder<static>|Webhook query()
 * @method static Builder<static>|Webhook whereCreatedAt($value)
 * @method static Builder<static>|Webhook whereEndpoint($value)
 * @method static Builder<static>|Webhook whereEvent($value)
 * @method static Builder<static>|Webhook whereId($value)
 * @method static Builder<static>|Webhook wherePayload($value)
 * @method static Builder<static>|Webhook whereSuccessfulAt($value)
 * @method static Builder<static>|Webhook whereUpdatedAt($value)
 * @method static Builder<static>|Webhook whereWebhookConfigurationId($value)
 */
class Webhook extends Model
{
    use HasFactory, MassPrunable;

    protected $fillable = ['payload', 'successful_at', 'event', 'endpoint'];

    public function casts()
    {
        return [
            'payload' => 'array',
            'successful_at' => 'datetime',
        ];
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', Carbon::now()->subDays(config('panel.webhook.prune_days')));
    }
}
