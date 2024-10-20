<?php

namespace App\Tests\Integration\Api\Client;

use App\Models\Node;
use App\Models\Task;
use App\Models\User;
use App\Models\Model;
use App\Models\Backup;
use App\Models\Server;
use App\Models\Database;
use App\Models\Schedule;
use Illuminate\Support\Collection;
use App\Models\Allocation;
use App\Models\DatabaseHost;
use App\Tests\Integration\TestResponse;
use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use App\Transformers\Api\Client\BaseClientTransformer;

abstract class ClientApiIntegrationTestCase extends IntegrationTestCase
{
    /**
     * Cleanup after running tests.
     */
    protected function tearDown(): void
    {
        Database::query()->forceDelete();
        DatabaseHost::query()->forceDelete();
        Backup::query()->forceDelete();
        Server::query()->forceDelete();
        Node::query()->forceDelete();
        User::query()->forceDelete();

        parent::tearDown();
    }

    /**
     * Override the default createTestResponse from Illuminate so that we can
     * just dump 500-level errors to the screen in the tests without having
     * to keep re-assigning variables.
    protected function createTestResponse($response, $request): \Illuminate\Testing\TestResponse
    {
        return TestResponse::fromBaseResponse($response);
    }

    /**
     * Returns a link to the specific resource using the client API.
     */
    protected function link(mixed $model, ?string $append = null): string
    {
        switch (get_class($model)) {
            case Server::class:
                $link = "/api/client/servers/$model->uuid";
                break;
            case Schedule::class:
                $link = "/api/client/servers/{$model->server->uuid}/schedules/$model->id";
                break;
            case Task::class:
                $link = "/api/client/servers/{$model->schedule->server->uuid}/schedules/{$model->schedule->id}/tasks/$model->id";
                break;
            case Allocation::class:
                $link = "/api/client/servers/{$model->server->uuid}/network/allocations/$model->id";
                break;
            case Backup::class:
                $link = "/api/client/servers/{$model->server->uuid}/backups/$model->uuid";
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Cannot create link for Model of type %s', class_basename($model)));
        }

        return $link . ($append ? '/' . ltrim($append, '/') : '');
    }

    /**
     * Asserts that the data passed through matches the output of the data from the transformer. This
     * will remove the "relationships" key when performing the comparison.
     */
    protected function assertJsonTransformedWith(array $data, Model|EloquentModel $model): void
    {
        $reflect = new \ReflectionClass($model);
        $transformer = sprintf('\\App\\Transformers\\Api\\Client\\%sTransformer', $reflect->getShortName());

        $transformer = new $transformer();
        $this->assertInstanceOf(BaseClientTransformer::class, $transformer);

        $this->assertSame(
            $transformer->transform($model),
            Collection::make($data)->except(['relationships'])->toArray()
        );
    }
}
