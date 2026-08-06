<?php

namespace App\Tests\Integration\Api\Application;

use App\Enums\WebhookScope;
use App\Extensions\Webhooks\Schemas\BaseSchema;
use App\Extensions\Webhooks\WebhookTypeService;
use App\Models\Server;
use App\Models\Webhook;
use App\Models\WebhookConfiguration;
use App\Services\Acl\Api\AdminAcl;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class WebhookControllerTest extends ApplicationApiIntegrationTestCase
{
    public function test_list_all_webhooks(): void
    {
        WebhookConfiguration::factory()->count(2)->create();

        $response = $this->getJson('/api/application/webhooks');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'object',
            'data' => [
                [
                    'object',
                    'attributes' => [
                        'id', 'name', 'description', 'scope', 'server_id', 'type', 'type_available',
                        'endpoint', 'events', 'payload', 'headers', 'created_at', 'updated_at',
                    ],
                ],
            ],
        ]);
    }

    public function test_view_single_webhook(): void
    {
        $webhook = WebhookConfiguration::factory()->create();

        $response = $this->getJson('/api/application/webhooks/' . $webhook->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('attributes.id', $webhook->id);
        $response->assertJsonPath('attributes.name', $webhook->name);
        $response->assertJsonPath('attributes.type', WebhookTypeService::Default);
        $response->assertJsonPath('attributes.type_available', true);
    }

    public function test_it_lists_registered_types(): void
    {
        $response = $this->getJson('/api/application/webhooks/types');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            'id' => WebhookTypeService::Default,
            'has_preview' => false,
        ]);
    }

    public function test_it_lists_events_for_the_requested_scope(): void
    {
        $global = $this->getJson('/api/application/webhooks/events');
        $global->assertStatus(Response::HTTP_OK);
        $global->assertJsonPath('meta.scope', WebhookScope::Global->value);

        $server = $this->getJson('/api/application/webhooks/events?scope=server');
        $server->assertStatus(Response::HTTP_OK);
        $server->assertJsonPath('meta.scope', WebhookScope::Server->value);
        $server->assertJsonFragment(['event' => 'server:power.start']);
    }

    public function test_it_creates_a_webhook(): void
    {
        $response = $this->postJson('/api/application/webhooks', [
            'name' => 'Deploy notifier',
            'description' => 'Pings on server creation',
            'endpoint' => 'https://example.com/hook',
            'events' => ['eloquent.created: ' . Server::class],
            'headers' => ['X-Webhook-Event' => '{{event}}'],
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonPath('attributes.name', 'Deploy notifier');
        $response->assertJsonPath('attributes.scope', WebhookScope::Global->value);
        $response->assertJsonPath('attributes.type', WebhookTypeService::Default);

        $this->assertDatabaseHas(WebhookConfiguration::class, [
            'name' => 'Deploy notifier',
            'endpoint' => 'https://example.com/hook',
        ]);
    }

    public function test_it_rejects_an_unregistered_type(): void
    {
        $response = $this->postJson('/api/application/webhooks', [
            'name' => 'Bad type',
            'endpoint' => 'https://example.com/hook',
            'type' => 'not-a-registered-type',
            'events' => ['eloquent.created: ' . Server::class],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseCount(WebhookConfiguration::class, 0);
    }

    public function test_it_rejects_an_unknown_event(): void
    {
        $response = $this->postJson('/api/application/webhooks', [
            'name' => 'Bad event',
            'endpoint' => 'https://example.com/hook',
            'events' => ['definitely.not.an.event'],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseCount(WebhookConfiguration::class, 0);
    }

    public function test_it_rejects_a_server_scope_without_a_server(): void
    {
        $response = $this->postJson('/api/application/webhooks', [
            'name' => 'Server scoped',
            'endpoint' => 'https://example.com/hook',
            'scope' => WebhookScope::Server->value,
            'events' => ['server:power.start'],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonPath('errors.0.meta.source_field', 'server_id');
    }

    public function test_it_updates_a_webhook(): void
    {
        $webhook = WebhookConfiguration::factory()->create([
            'events' => ['eloquent.created: ' . Server::class],
        ]);

        $response = $this->patchJson('/api/application/webhooks/' . $webhook->id, [
            'name' => 'Renamed',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('attributes.name', 'Renamed');
        $this->assertSame('Renamed', $webhook->refresh()->name);
    }

    public function test_it_deletes_a_webhook(): void
    {
        $webhook = WebhookConfiguration::factory()->create();

        $this->deleteJson('/api/application/webhooks/' . $webhook->id)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSoftDeleted($webhook);
    }

    public function test_it_fires_a_test_webhook(): void
    {
        $webhook = WebhookConfiguration::factory()->create([
            'events' => ['eloquent.created: ' . Server::class],
        ]);

        Http::fake([$webhook->endpoint => Http::response()]);

        $response = $this->postJson('/api/application/webhooks/' . $webhook->id . '/test');

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJsonPath('dispatched', true);

        Http::assertSent(fn ($request) => $request->url() === $webhook->endpoint);
        $this->assertDatabaseCount(Webhook::class, 1);
    }

    public function test_it_enforces_the_payload_rules_a_type_declares(): void
    {
        $this->app->make(WebhookTypeService::class)->register(new LimitedPayloadSchema());

        $body = [
            'name' => 'Limited',
            'endpoint' => 'https://example.com/hook',
            'type' => 'limited',
            'events' => ['eloquent.created: ' . Server::class],
        ];

        $this->postJson('/api/application/webhooks', $body + ['payload' => ['note' => 'way too long']])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('errors.0.meta.source_field', 'payload.note');

        $this->postJson('/api/application/webhooks', $body + ['payload' => ['note' => 'ok']])
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function test_it_requires_permission(): void
    {
        $webhook = WebhookConfiguration::factory()->create();

        $this->createNewDefaultApiKey($this->getApiUser(), [
            WebhookConfiguration::RESOURCE_NAME => AdminAcl::NONE,
        ]);

        $this->getJson('/api/application/webhooks')->assertForbidden();
        $this->postJson('/api/application/webhooks/' . $webhook->id . '/test')->assertForbidden();
    }
}

class LimitedPayloadSchema extends BaseSchema
{
    public function getId(): string
    {
        return 'limited';
    }

    /** @return array<string, mixed> */
    public function getPayloadRules(): array
    {
        return ['note' => ['string', 'max:5']];
    }
}
