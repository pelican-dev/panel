<?php

namespace App\Tests\Integration\Api\Application\Users;

use App\Events\ActivityLogged;
use App\Models\ApiKey;
use App\Models\User;
use App\Services\Acl\Api\AdminAcl;
use App\Tests\Integration\Api\Application\ApplicationApiIntegrationTestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\DataProvider;

class ApiKeyControllerTest extends ApplicationApiIntegrationTestCase
{
    public function test_api_key_can_be_created_for_user(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/application/users/' . $user->id . '/api-keys', [
            'description' => 'Mado Hosting Control Panel',
            'allowed_ips' => [],
        ]);

        $response->assertOk()
            ->assertJsonPath('object', ApiKey::RESOURCE_NAME)
            ->assertJsonStructure([
                'object',
                'attributes' => [
                    'identifier',
                    'description',
                    'allowed_ips',
                    'last_used_at',
                    'created_at',
                ],
                'meta' => ['secret_token'],
            ]);

        /** @var ApiKey $key */
        $key = ApiKey::query()->where('identifier', $response->json('attributes.identifier'))->firstOrFail();

        $this->assertSame(ApiKey::TYPE_ACCOUNT, $key->key_type);
        $this->assertSame($user->id, $key->user_id);
        $this->assertSame('Mado Hosting Control Panel', $key->memo);
        $this->assertSame([], $key->allowed_ips);
        $this->assertSame($key->token, $response->json('meta.secret_token'));
        $this->assertStringStartsWith(ApiKey::getPrefixForType(ApiKey::TYPE_ACCOUNT), $response->json('attributes.identifier'));

        $this->assertActivityFor('user:api-key.create', $this->getApiUser(), [$key, $user]);
    }

    public function test_api_keys_are_returned_without_secret_token(): void
    {
        $user = User::factory()->create();
        /** @var ApiKey $key */
        $key = ApiKey::factory()->for($user)->create([
            'key_type' => ApiKey::TYPE_ACCOUNT,
            'memo' => 'Mado Hosting Control Panel',
            'allowed_ips' => ['127.0.0.1'],
        ]);

        ApiKey::factory()->create([
            'user_id' => User::factory()->create()->id,
            'key_type' => ApiKey::TYPE_ACCOUNT,
        ]);

        $response = $this->getJson('/api/application/users/' . $user->id . '/api-keys');

        $response->assertOk()
            ->assertJsonPath('object', 'list')
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.object', ApiKey::RESOURCE_NAME)
            ->assertJsonPath('data.0.attributes.identifier', $key->identifier)
            ->assertJsonPath('data.0.attributes.description', 'Mado Hosting Control Panel')
            ->assertJsonPath('data.0.attributes.allowed_ips', ['127.0.0.1'])
            ->assertJsonMissingPath('data.0.meta.secret_token')
            ->assertJsonMissingPath('data.0.attributes.token');
    }

    public function test_api_key_can_be_deleted_for_user(): void
    {
        $user = User::factory()->create();
        /** @var ApiKey $key */
        $key = ApiKey::factory()->for($user)->create([
            'key_type' => ApiKey::TYPE_ACCOUNT,
        ]);

        $response = $this->deleteJson('/api/application/users/' . $user->id . '/api-keys/' . $key->identifier);

        $response->assertOk();
        $this->assertSame([], $response->json());
        $this->assertDatabaseMissing('api_keys', ['id' => $key->id]);
        $this->assertActivityFor('user:api-key.delete', $this->getApiUser(), [$key, $user]);
    }

    public function test_api_key_belonging_to_another_user_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        /** @var ApiKey $key */
        $key = ApiKey::factory()->for($otherUser)->create([
            'key_type' => ApiKey::TYPE_ACCOUNT,
        ]);

        $response = $this->deleteJson('/api/application/users/' . $user->id . '/api-keys/' . $key->identifier);

        $this->assertNotFoundJson($response);
        $this->assertDatabaseHas('api_keys', ['id' => $key->id]);
        Event::assertNotDispatched(ActivityLogged::class);
    }

    public function test_application_api_key_cannot_be_deleted_as_client_key(): void
    {
        $user = User::factory()->create();
        /** @var ApiKey $key */
        $key = ApiKey::factory()->for($user)->create([
            'key_type' => ApiKey::TYPE_APPLICATION,
        ]);

        $response = $this->deleteJson('/api/application/users/' . $user->id . '/api-keys/' . $key->identifier);

        $this->assertNotFoundJson($response);
        $this->assertDatabaseHas('api_keys', ['id' => $key->id]);
    }

    public function test_api_key_validation_errors_are_returned(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/application/users/' . $user->id . '/api-keys', [
            'description' => '',
            'allowed_ips' => [],
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.0.meta.rule', 'required')
            ->assertJsonPath('errors.0.detail', 'The description field is required.');

        $this->postJson('/api/application/users/' . $user->id . '/api-keys', [
            'description' => 'Mado Hosting Control Panel',
            'allowed_ips' => ['hodor', '127.0.0.1', 'hodor/24'],
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.0.detail', '"hodor" is not a valid IP address or CIDR range.')
            ->assertJsonPath('errors.0.meta.source_field', 'allowed_ips.0')
            ->assertJsonPath('errors.1.detail', '"hodor/24" is not a valid IP address or CIDR range.')
            ->assertJsonPath('errors.1.meta.source_field', 'allowed_ips.2');
    }

    public function test_api_key_limit_is_applied_to_target_user(): void
    {
        $user = User::factory()->create();
        ApiKey::factory()->times(config('panel.api.key_limit'))->for($user)->create([
            'key_type' => ApiKey::TYPE_ACCOUNT,
        ]);

        $this->postJson('/api/application/users/' . $user->id . '/api-keys', [
            'description' => 'Mado Hosting Control Panel',
            'allowed_ips' => [],
        ])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonPath('errors.0.code', 'DisplayException')
            ->assertJsonPath('errors.0.detail', 'You have reached the account limit for number of API keys.');
    }

    #[DataProvider('userApiKeyWriteEndpointsDataProvider')]
    public function test_api_key_without_write_permissions_cannot_create_or_delete_keys(string $method, string $url): void
    {
        $user = User::factory()->create();
        /** @var ApiKey $key */
        $key = ApiKey::factory()->for($user)->create([
            'key_type' => ApiKey::TYPE_ACCOUNT,
        ]);

        $this->createNewDefaultApiKey($this->getApiUser(), [User::RESOURCE_NAME => AdminAcl::READ]);

        $url = str_replace(['{user}', '{identifier}'], [$user->id, $key->identifier], $url);
        $response = $this->$method($url, [
            'description' => 'Mado Hosting Control Panel',
            'allowed_ips' => [],
        ]);

        $this->assertAccessDeniedJson($response);
    }

    public static function userApiKeyWriteEndpointsDataProvider(): array
    {
        return [
            ['postJson', '/api/application/users/{user}/api-keys'],
            ['deleteJson', '/api/application/users/{user}/api-keys/{identifier}'],
        ];
    }
}
