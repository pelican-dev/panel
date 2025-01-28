<?php

namespace App\Tests\Integration\Api\Application\Users;

use Illuminate\Support\Str;
use App\Models\User;
use App\Services\Acl\Api\AdminAcl;
use Illuminate\Http\Response;
use App\Tests\Integration\Api\Application\ApplicationApiIntegrationTestCase;

class ExternalUserControllerTest extends ApplicationApiIntegrationTestCase
{
    /**
     * Test that a user can be retrieved by their external ID.
     */
    public function testGetRemoteUser(): void
    {
        $user = User::factory()->create(['external_id' => Str::random()]);

        $response = $this->getJson('/api/application/users/external/' . $user->external_id);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(2);
        $response->assertJsonStructure([
            'object',
            'attributes' => [
                'id', 'external_id', 'uuid', 'username', 'email',
                'language', 'root_admin', '2fa', 'created_at', 'updated_at',
            ],
        ]);

        $response->assertJson([
            'object' => 'user',
            'attributes' => [
                'id' => $user->id,
                'external_id' => $user->external_id,
                'uuid' => $user->uuid,
                'username' => $user->username,
                'email' => $user->email,
                'language' => $user->language,
                'root_admin' => (bool) $user->isRootAdmin(),
                '2fa' => (bool) $user->totp_enabled,
                'created_at' => $this->formatTimestamp($user->created_at),
                'updated_at' => $this->formatTimestamp($user->updated_at),
            ],
        ], true);
    }

    /**
     * Test that an invalid external ID returns a 404 error.
     */
    public function testGetMissingUser(): void
    {
        $response = $this->getJson('/api/application/users/external/nil');
        $this->assertNotFoundJson($response);
    }

    /**
     * Test that an authentication error occurs if a key does not have permission
     * to access a resource.
     */
    public function testErrorReturnedIfNoPermission(): void
    {
        $user = User::factory()->create(['external_id' => Str::random()]);
        $this->createNewDefaultApiKey($this->getApiUser(), [User::RESOURCE_NAME => AdminAcl::NONE]);

        $response = $this->getJson('/api/application/users/external/' . $user->external_id);
        $this->assertAccessDeniedJson($response);
    }
}
