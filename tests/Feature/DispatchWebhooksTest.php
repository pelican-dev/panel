<?php

namespace App\Tests\Feature;

use App\Events\Server\Created;
use App\Events\Server\Deleted;
use App\Listeners\DispatchWebhooks;
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

        $webhook = WebhookConfiguration::factory()->create(['events' => [Created::class]]);

        Http::fake([$webhook->endpoint => Http::response()]);

        $server = Server::factory()->make();

        $event = new Created($server);

        $whl = new DispatchWebhooks();

        $whl->handle(get_class($event), [$event]);

        Http::assertSentCount(1);
        Http::assertSent(function (Request $request) use ($webhook, $event, $server) {
            return $webhook->endpoint === $request->url()
                && $request[0]->server === $server;
        });
    }

    public function test_sends_multiple_webhooks()
    {
        Http::preventStrayRequests();

        [$webhook1, $webhook2] = WebhookConfiguration::factory(2)
            ->create(['events' => [Created::class]]);

        Http::fake([
            $webhook1->endpoint => Http::response(),
            $webhook2->endpoint => Http::response(),
        ]);

        $event = new Created(Server::factory()->make());

        $whl = new DispatchWebhooks();

        $whl->handle(get_class($event), [$event]);

        Http::assertSentCount(2);
        Http::assertSent(fn(Request $request) => $webhook1->endpoint === $request->url());
        Http::assertSent(fn(Request $request) => $webhook2->endpoint === $request->url());
    }

    public function test_it_sends_no_webhooks()
    {
        Http::preventStrayRequests();
        Http::fake();

        WebhookConfiguration::factory()->create();

        $event = new Created(Server::factory()->make());

        $whl = new DispatchWebhooks();

        $whl->handle(get_class($event), [$event]);

        Http::assertSentCount(0);
    }

    public function test_it_sends_some_webhooks()
    {
        Http::preventStrayRequests();

        [$webhook1, $webhook2] = WebhookConfiguration::factory(2)
            ->sequence(
                ['events' => [Created::class]],
                ['events' => [Deleted::class]]
            )->create();

        Http::fake([
            $webhook1->endpoint => Http::response(),
            $webhook2->endpoint => Http::response(),
        ]);

        $event = new Created(Server::factory()->make());

        $whl = new DispatchWebhooks();

        $whl->handle(get_class($event), [$event]);

        Http::assertSentCount(1);
        Http::assertSent(fn(Request $request) => $webhook1->endpoint === $request->url());
        Http::assertNotSent(fn(Request $request) => $webhook2->endpoint === $request->url());
    }
    
    public function test_it_records_when_a_webhook_is_sent() {

        Carbon::setTestNow();
        Http::preventStrayRequests();

        $webhookConfig = WebhookConfiguration::factory()->create(['events' => [Created::class]]);

        Http::fake([$webhookConfig->endpoint => Http::response()]);

        $server = Server::factory()->make();

        $this->assertDatabaseCount(Webhook::class, 0);

        $event = new Created($server);

        $whl = new DispatchWebhooks();

        $whl->handle($event::class, [$event]);

        $this->assertDatabaseCount(Webhook::class, 1);
        $this->assertDatabaseHas(Webhook::class, [
            'payload' => json_encode([['server' => $server->toArray()]]),
            'endpoint' => $webhookConfig->endpoint,
            'successful_at' => now()->startOfSecond(),
            'event' => Created::class,
        ]);
    }

    public function test_it_records_when_a_webhook_fails() {
        Carbon::setTestNow();
        Http::preventStrayRequests();

        $webhookConfig = WebhookConfiguration::factory()->create(['events' => [Created::class]]);

        Http::fake([$webhookConfig->endpoint => Http::response(status: 500)]);

        $server = Server::factory()->make();

        $this->assertDatabaseCount(Webhook::class, 0);

        $event = new Created($server);

        $whl = new DispatchWebhooks();

        $whl->handle($event::class, [$event]);

        $this->assertDatabaseCount(Webhook::class, 1);
        $this->assertDatabaseHas(Webhook::class, [
            'payload' => json_encode([['server' => $server->toArray()]]),
            'endpoint' => $webhookConfig->endpoint,
            'successful_at' => null,
            'event' => Created::class,
        ]);
    }
}
