<?php

namespace App\Tests\Integration\Api\Application\Users;

use App\Models\ApiKey;
use App\Models\User;
use App\Services\Acl\Api\AdminAcl;
use App\Tests\Integration\Api\Application\ApplicationApiIntegrationTestCase;
use App\Transformers\Api\Application\ApiKeyTransformer;
use Illuminate\Http\Response;

class UserApiKeyControllerTest extends ApplicationApiIntegrationTestCase
{
    public function test_user_api_keys_are_listed(): void
    {
        $user = User::factory()->create();
        $firstKey = ApiKey::factory()->for($user)->create([
            'key_type' => ApiKey::TYPE_ACCOUNT,
            'memo' => 'first key',
        ]);
        $secondKey = ApiKey::factory()->for($user)->create([
            'key_type' => ApiKey::TYPE_ACCOUNT,
            'memo' => 'second key',
        ]);

        $firstKey->refresh();
        $secondKey->refresh();

        $response = $this->getJson('/api/application/users/' . $user->id . '/api-keys')
            ->assertOk()
            ->assertJsonPath('object', 'list')
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.object', ApiKey::RESOURCE_NAME);

        $transformer = $this->getTransformer(ApiKeyTransformer::class);

        $this->assertSame($transformer->transform($secondKey), $response->json('data.0.attributes'));
        $this->assertSame($transformer->transform($firstKey), $response->json('data.1.attributes'));
    }

    public function test_user_api_key_can_be_created(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/application/users/' . $user->id . '/api-keys', [
            'description' => 'Test description',
            'allowed_ips' => ['127.0.0.1'],
        ])
            ->assertOk()
            ->assertJsonPath('object', ApiKey::RESOURCE_NAME);

        /** @var ApiKey $key */
        $key = ApiKey::query()->where('identifier', $response->json('attributes.identifier'))->firstOrFail();

        $transformer = $this->getTransformer(ApiKeyTransformer::class);

        $this->assertSame($transformer->transform($key), $response->json('attributes'));
        $this->assertSame($key->token, $response->json('meta.secret_token'));

        $this->assertActivityFor('user:api-key.create', $this->getApiUser(), [$key, $user]);
    }

    public function test_api_key_limit_is_enforced(): void
    {
        $user = User::factory()->create();
        ApiKey::factory()->times(config('panel.api.key_limit', 25))->for($user)->create([
            'key_type' => ApiKey::TYPE_ACCOUNT,
        ]);

        $this->postJson('/api/application/users/' . $user->id . '/api-keys', [
            'description' => 'Test description',
            'allowed_ips' => ['127.0.0.1'],
        ])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonPath('errors.0.code', 'DisplayException')
            ->assertJsonPath('errors.0.detail', 'This user has reached the account limit for number of API keys.');
    }

    public function test_user_api_key_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $key = ApiKey::factory()->for($user)->create([
            'key_type' => ApiKey::TYPE_ACCOUNT,
        ]);

        $this->deleteJson('/api/application/users/' . $user->id . '/api-keys/' . $key->identifier)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('api_keys', ['id' => $key->id]);

        $this->assertActivityFor('user:api-key.delete', $this->getApiUser(), [$user, $key]);
    }

    public function test_request_without_permission_is_denied(): void
    {
        $this->createNewDefaultApiKey($this->getApiUser(), [User::RESOURCE_NAME => AdminAcl::NONE]);

        $user = User::factory()->create();

        $response = $this->getJson('/api/application/users/' . $user->id . '/api-keys');
        $this->assertAccessDeniedJson($response);
    }
}
