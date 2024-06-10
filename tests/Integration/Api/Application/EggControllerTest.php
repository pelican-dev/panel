<?php

namespace App\Tests\Integration\Api\Application;

use App\Models\Egg;
use App\Services\Acl\Api\AdminAcl;
use App\Transformers\Api\Application\EggTransformer;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class EggControllerTest extends ApplicationApiIntegrationTestCase
{
    /**
     * Test that all the eggs can be returned.
     */
    public function testListAllEggs(): void
    {
        $eggs = Egg::query()->get();

        $response = $this->getJson('/api/application/eggs');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(count($eggs), 'data');
        $response->assertJsonStructure([
            'object',
            'data' => [
                [
                    'object',
                    'attributes' => [
                        'id', 'uuid', 'author', 'description', 'docker_image', 'startup', 'created_at', 'updated_at',
                        'script' => ['privileged', 'install', 'entry', 'container', 'extends'],
                        'config' => [
                            'files' => [],
                            'startup' => ['done'],
                            'stop',
                            'logs' => [],
                            'extends',
                        ],
                    ],
                ],
            ],
        ]);

        foreach (array_get($response->json(), 'data') as $datum) {
            $egg = $eggs->where('id', '=', $datum['attributes']['id'])->first();

            $expected = json_encode(Arr::sortRecursive($datum['attributes']));
            $actual = json_encode(Arr::sortRecursive($this->getTransformer(EggTransformer::class)->transform($egg)));

            $this->assertSame(
                $expected,
                $actual,
                'Unable to find JSON fragment: ' . PHP_EOL . PHP_EOL . "[$expected]" . PHP_EOL . PHP_EOL . 'within' . PHP_EOL . PHP_EOL . "[$actual]."
            );
        }
    }

    /**
     * Test that a single egg can be returned.
     */
    public function testReturnSingleEgg(): void
    {
        $egg = Egg::query()->findOrFail(1);

        $response = $this->getJson('/api/application/eggs/' . $egg->id);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'object',
            'attributes' => [
                'id', 'uuid', 'author', 'description', 'docker_image', 'startup', 'script' => [], 'config' => [], 'created_at', 'updated_at',
            ],
        ]);

        $response->assertJson([
            'object' => 'egg',
            'attributes' => $this->getTransformer(EggTransformer::class)->transform($egg),
        ], true);
    }

    /**
     * Test that a single egg and all the defined relationships can be returned.
     */
    public function testReturnSingleEggWithRelationships(): void
    {
        $egg = Egg::query()->findOrFail(1);

        $response = $this->getJson('/api/application/eggs/' . $egg->id . '?include=servers,variables');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'object',
            'attributes' => [
                'relationships' => [
                    'servers' => ['object', 'data' => []],
                    'variables' => ['object', 'data' => []],
                ],
            ],
        ]);
    }

    /**
     * Test that a missing egg returns a 404 error.
     */
    public function testGetMissingEgg(): void
    {
        $response = $this->getJson('/api/application/eggs/nil');
        $this->assertNotFoundJson($response);
    }

    /**
     * Test that an authentication error occurs if a key does not have permission
     * to access a resource.
     */
    public function testErrorReturnedIfNoPermission(): void
    {
        $egg = Egg::query()->findOrFail(1);
        $this->createNewDefaultApiKey($this->getApiUser(), [Egg::RESOURCE_NAME => AdminAcl::NONE]);

        $response = $this->getJson('/api/application/eggs');
        $this->assertAccessDeniedJson($response);
    }
}
