<?php

namespace App\Tests\Feature;

use App\Models\Node;
use App\Models\Server;
use App\Models\Webhook;
use App\Models\WebhookConfiguration;
use App\Tests\TestCase;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class DispatchWebhooksTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_it_sends_a_single_webhook(): void
    {
        Http::preventStrayRequests();

        $webhook = WebhookConfiguration::factory()->create([
            'events' => ['eloquent.created: ' . Server::class],
        ]);

        Http::fake([$webhook->endpoint => Http::response()]);

        $node = Node::factory()->create();
        $server = Server::factory()->withNode($node)->create();

        Http::assertSentCount(1);
        Http::assertSent(function (Request $request) use ($webhook, $server) {
            return $webhook->endpoint === $request->url()
                && $request[0] === $server;
        });
    }

    public function test_sends_multiple_webhooks()
    {
        Http::preventStrayRequests();

        [$webhook1, $webhook2] = WebhookConfiguration::factory(2)
            ->create(['events' => ['eloquent.created: ' . Server::class]]);

        Http::fake([
            $webhook1->endpoint => Http::response(),
            $webhook2->endpoint => Http::response(),
        ]);

        $node = Node::factory()->create();
        $server = Server::factory()->withNode($node)->create();

        Http::assertSentCount(2);
        Http::assertSent(fn (Request $request) => $webhook1->endpoint === $request->url());
        Http::assertSent(fn (Request $request) => $webhook2->endpoint === $request->url());
    }

    public function test_it_sends_no_webhooks()
    {
        Http::preventStrayRequests();
        Http::fake();

        WebhookConfiguration::factory()->create();

        $node = Node::factory()->create();
        $server = Server::factory()->withNode($node)->create();

        Http::assertSentCount(0);
    }

    public function test_it_sends_some_webhooks()
    {
        Http::preventStrayRequests();

        [$webhook1, $webhook2] = WebhookConfiguration::factory(2)
            ->sequence(
                ['events' => ['eloquent.created: ' . Server::class]],
                ['events' => ['eloquent.deleted: ' . Server::class]]
            )->create();

        Http::fake([
            $webhook1->endpoint => Http::response(),
            $webhook2->endpoint => Http::response(),
        ]);

        $node = Node::factory()->create();
        $server = Server::factory()->withNode($node)->create();

        Http::assertSentCount(1);
        Http::assertSent(fn (Request $request) => $webhook1->endpoint === $request->url());
        Http::assertNotSent(fn (Request $request) => $webhook2->endpoint === $request->url());
    }

    public function test_it_records_when_a_webhook_is_sent()
    {

        Carbon::setTestNow();
        Http::preventStrayRequests();

        $webhookConfig = WebhookConfiguration::factory()
            ->create(['events' => ['eloquent.created: ' . Server::class]]);

        Http::fake([$webhookConfig->endpoint => Http::response()]);

        $this->assertDatabaseCount(Webhook::class, 0);

        $node = Node::factory()->create();
        $server = Server::factory()->withNode($node)->create();

        $this->assertDatabaseCount(Webhook::class, 1);

        $webhook = Webhook::query()->first();
        $this->assertEquals($server->uuid, $webhook->payload[0]['uuid']);

        $this->assertDatabaseHas(Webhook::class, [
            'endpoint' => $webhookConfig->endpoint,
            'successful_at' => now()->startOfSecond(),
            'event' => 'eloquent.created: ' . Server::class,
        ]);
    }

    public function test_it_records_when_a_webhook_fails()
    {
        Carbon::setTestNow();
        Http::preventStrayRequests();

        $webhookConfig = WebhookConfiguration::factory()->create(['events' => ['eloquent.created: ' . Server::class],
        ]);

        Http::fake([$webhookConfig->endpoint => Http::response(status: 500)]);

        $this->assertDatabaseCount(Webhook::class, 0);

        $node = Node::factory()->create();
        $server = Server::factory()->withNode($node)->create();

        $this->assertDatabaseCount(Webhook::class, 1);
        $this->assertDatabaseHas(Webhook::class, [
            // 'payload' => json_encode([['server' => $server->toArray()]]),
            'endpoint' => $webhookConfig->endpoint,
            'successful_at' => null,
            'event' => 'eloquent.created: ' . Server::class,
        ]);
    }
}
