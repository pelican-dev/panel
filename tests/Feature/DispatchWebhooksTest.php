<?php

namespace App\Tests\Feature;

use App\Events\Server\Created;
use App\Events\Server\Deleted;
use App\Listeners\DispatchWebhooks;
use App\Models\Server;
use App\Models\WebhookConfiguration;
use App\Tests\TestCase;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class DispatchWebhooksTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_it_sends_a_single_webhook(): void
    {
        \Http::preventStrayRequests();

        $url = fake()->url();

        $webhook = WebhookConfiguration::factory()->create([
            'endpoint' => $url,
            'events' => [Created::class],
            'description' => '',
        ]);

        Http::fake([
            $url => Http::response(),
        ]);

        $event = new Created(Server::factory()->make());

        $whl = new DispatchWebhooks();

        $whl->handle(get_class($event), [$event]);

        Http::assertSentCount(1);
        Http::assertSent(function (Request $request) use ($url) {
            return $url == $request->url();
        });
    }

    public function test_sends_multiple_webhooks()
    {
        Http::preventStrayRequests();

        [$webhook1, $webhook2] = WebhookConfiguration::factory(2)
            ->sequence(['endpoint' => fake()->url()], ['endpoint' => fake()->url()])
            ->create([
                'events' => [Created::class],
                'description' => '',
            ]);

        Http::fake([
            $webhook1->endpoint => Http::response(),
            $webhook2->endpoint => Http::response(),
        ]);

        $event = new Created(Server::factory()->make());

        $whl = new DispatchWebhooks();

        $whl->handle(get_class($event), [$event]);

        Http::assertSentCount(2);
        Http::assertSent(function (Request $request) use ($webhook1) {
            return $webhook1->endpoint == $request->url();
        });
        Http::assertSent(function (Request $request) use ($webhook2) {
            return $webhook2->endpoint == $request->url();
        });
    }

    public function test_it_sends_no_webhooks()
    {
        Http::preventStrayRequests();
        Http::fake();

        WebhookConfiguration::factory()
            ->create([
                'endpoint' => fake()->url(),
                'events' => [],
                'description' => '',
            ]);

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
                ['endpoint' => fake()->url(), 'events' => [Created::class]],
                ['endpoint' => fake()->url(), 'events' => [Deleted::class]]
            )->create([
                'description' => '',
            ]);

        Http::fake([
            $webhook1->endpoint => Http::response(),
            $webhook2->endpoint => Http::response(),
        ]);

        $event = new Created(Server::factory()->make());

        $whl = new DispatchWebhooks();

        $whl->handle(get_class($event), [$event]);

        Http::assertSentCount(1);
        Http::assertSent(function (Request $request) use ($webhook1) {
            return $webhook1->endpoint == $request->url();
        });
        Http::assertNotSent(function (Request $request) use ($webhook2) {
            return $webhook2->endpoint == $request->url();
        });
    }
}
