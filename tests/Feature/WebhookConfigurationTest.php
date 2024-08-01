<?php

namespace App\Tests\Feature;

use App\Events\Server\Created;
use App\Jobs\DispatchWebhooksJob;
use App\Models\Server;
use App\Models\WebhookConfiguration;
use App\Tests\TestCase;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

class WebhookConfigurationTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        \Http::preventStrayRequests();
    }

    public function test_event_triggers_dispatch_webhooks_job(): void
    {
        $server = Server::factory()->make();
        // A server is created
        Event::dispatch(new Created($server));

        // creates a SeverCreatedEvent

        // verify that the job for handling the webhook is dispatched

        Queue::assertPushed(DispatchWebhooksJob::class);
    }

    public function test_webhook_configuration_does_the_right_thing()
    {
        $webhookConfiguration = WebhookConfiguration::factory()->make([
            'events' => [
                Created::class,
            ]
        ]);

        \Http::fake([
            $webhookConfiguration->endpoint => \Http::response(),
        ]);

        $server = Server::factory()->make();

        $job = new DispatchWebhooksJob(Created::class, $server);

        $job->handle();

        \Http::assertSentCount(1);

    }
}
