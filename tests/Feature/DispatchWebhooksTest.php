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

    public function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        Carbon::setTestNow();
    }

    public function test_it_sends_a_single_webhook(): void
    {
        $webhook = WebhookConfiguration::factory()->create([
            'events' => ['eloquent.created: ' . Server::class],
        ]);

        Http::fake([$webhook->endpoint => Http::response()]);

        $server = $this->createServer();

        Http::assertSentCount(1);
        Http::assertSent(function (Request $request) use ($webhook, $server) {
            return $webhook->endpoint === $request->url()
                && $request[0] === $server;
        });
    }

    public function test_sends_multiple_webhooks()
    {

        [$webhook1, $webhook2] = WebhookConfiguration::factory(2)
            ->create(['events' => ['eloquent.created: ' . Server::class]]);

        Http::fake([
            $webhook1->endpoint => Http::response(),
            $webhook2->endpoint => Http::response(),
        ]);

        $this->createServer();

        Http::assertSentCount(2);
        Http::assertSent(fn (Request $request) => $webhook1->endpoint === $request->url());
        Http::assertSent(fn (Request $request) => $webhook2->endpoint === $request->url());
    }

    public function test_it_sends_no_webhooks()
    {
        Http::fake();

        WebhookConfiguration::factory()->create();

        $this->createServer();

        Http::assertSentCount(0);
    }

    public function test_it_sends_some_webhooks()
    {
        [$webhook1, $webhook2] = WebhookConfiguration::factory(2)
            ->sequence(
                ['events' => ['eloquent.created: ' . Server::class]],
                ['events' => ['eloquent.deleted: ' . Server::class]]
            )->create();

        Http::fake([
            $webhook1->endpoint => Http::response(),
            $webhook2->endpoint => Http::response(),
        ]);

        $this->createServer();

        Http::assertSentCount(1);
        Http::assertSent(fn (Request $request) => $webhook1->endpoint === $request->url());
        Http::assertNotSent(fn (Request $request) => $webhook2->endpoint === $request->url());
    }

    public function test_it_records_when_a_webhook_is_sent()
    {
        $webhookConfig = WebhookConfiguration::factory()
            ->create(['events' => ['eloquent.created: ' . Server::class]]);

        Http::fake([$webhookConfig->endpoint => Http::response()]);

        $this->assertDatabaseCount(Webhook::class, 0);

        $server = $this->createServer();

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
        $webhookConfig = WebhookConfiguration::factory()->create(['events' => ['eloquent.created: ' . Server::class],
        ]);

        Http::fake([$webhookConfig->endpoint => Http::response(status: 500)]);

        $this->assertDatabaseCount(Webhook::class, 0);

        $this->createServer();

        $this->assertDatabaseCount(Webhook::class, 1);
        $this->assertDatabaseHas(Webhook::class, [
            // 'payload' => json_encode([['server' => $server->toArray()]]),
            'endpoint' => $webhookConfig->endpoint,
            'successful_at' => null,
            'event' => 'eloquent.created: ' . Server::class,
        ]);
    }

    public function createServer(): Server
    {
        $node = Node::factory()->create();
        return Server::factory()->withNode($node)->create();
    }
}
