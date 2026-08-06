<?php

namespace App\Transformers\Api\Application;

use App\Facades\WebhookTypes;
use App\Models\Server;
use App\Models\WebhookConfiguration;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;

class WebhookConfigurationTransformer extends BaseTransformer
{
    /**
     * List of resources that can be included.
     */
    protected array $availableIncludes = ['server', 'deliveries'];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return WebhookConfiguration::RESOURCE_NAME;
    }

    /**
     * @param  WebhookConfiguration  $model
     */
    public function transform($model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description,
            'scope' => $model->scope->value,
            'server_id' => $model->server_id,
            'type' => $model->type,
            // False when the type comes from a plugin that is not currently loaded
            'type_available' => WebhookTypes::has($model->type),
            'endpoint' => $model->endpoint,
            'events' => $model->events,
            'payload' => $model->payload,
            'headers' => $model->headers,
            'created_at' => $model->created_at?->toAtomString(),
            'updated_at' => $model->updated_at?->toAtomString(),
        ];
    }

    /**
     * Return the server this webhook is scoped to, if any.
     */
    public function includeServer(WebhookConfiguration $webhookConfiguration): Item|NullResource
    {
        if (!$webhookConfiguration->server_id || !$this->authorize(Server::RESOURCE_NAME)) {
            return $this->null();
        }

        $webhookConfiguration->loadMissing('server');

        return $this->item(
            $webhookConfiguration->getRelation('server'),
            $this->makeTransformer(ServerTransformer::class),
            Server::RESOURCE_NAME
        );
    }

    /**
     * Return the delivery log for this webhook.
     */
    public function includeDeliveries(WebhookConfiguration $webhookConfiguration): Collection
    {
        $webhookConfiguration->loadMissing('webhooks');

        return $this->collection(
            $webhookConfiguration->getRelation('webhooks'),
            $this->makeTransformer(WebhookDeliveryTransformer::class),
            'webhook_delivery'
        );
    }
}
