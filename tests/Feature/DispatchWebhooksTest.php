<?php

namespace App\Tests\Feature;

use App\Events\Server\Created;
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

    public function test_example(): void
    {
        \Http::preventStrayRequests();

        $url = fake()->url();

        $webhook = WebhookConfiguration::factory()->make([
            'url' => $url,
            'events' => ['server.create'],
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
            $this->assertEquals($url, $request->url());
        });
    }
}
