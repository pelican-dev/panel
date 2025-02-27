<?php

namespace App\Tests\Feature\Webhooks;

use App\Models\Server;
use App\Tests\TestCase;
use App\Jobs\ProcessWebhook;
use App\Models\WebhookConfiguration;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

class DispatchWebhooksTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function testItSendsASingleWebhook(): void
    {
        WebhookConfiguration::factory()->create([
            'events' => ['eloquent.created: ' . Server::class],
        ]);

        $this->createServer();

        Queue::assertPushed(ProcessWebhook::class);
    }

    public function testSendsMultipleWebhooks(): void
    {
        WebhookConfiguration::factory(2)
            ->create(['events' => ['eloquent.created: ' . Server::class]]);

        $this->createServer();

        Queue::assertPushed(ProcessWebhook::class, 2);
    }

    public function testItSendsNoWebhooks(): void
    {
        WebhookConfiguration::factory()->create();

        $this->createServer();

        Queue::assertNothingPushed();
    }

    public function testItSendsSomeWebhooks(): void
    {
        WebhookConfiguration::factory(2)
            ->sequence(
                ['events' => ['eloquent.created: ' . Server::class]],
                ['events' => ['eloquent.deleted: ' . Server::class]]
            )->create();

        $this->createServer();

        Queue::assertPushed(ProcessWebhook::class, 1);
    }

    public function testItDoesNotCallRemovedEvents(): void
    {
        $webhookConfig = WebhookConfiguration::factory()->create([
            'events' => ['eloquent.created: ' . Server::class],
        ]);

        $webhookConfig->update(['events' => 'eloquent.deleted: ' . Server::class]);

        $this->createServer();

        Queue::assertNothingPushed();
    }

    public function testItDoesNotCallDeletedWebhooks(): void
    {
        $webhookConfig = WebhookConfiguration::factory()->create([
            'events' => ['eloquent.created: ' . Server::class],
        ]);

        $webhookConfig->delete();

        $this->createServer();

        Queue::assertNothingPushed();
    }

    public function createServer(): Server
    {
        return Server::factory()->withNode()->create();
    }
}
