<?php

namespace App\Tests\Integration\Api\Client;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Response;
use PragmaRX\Google2FA\Google2FA;
use App\Models\RecoveryToken;
use PHPUnit\Framework\ExpectationFailedException;

class TwoFactorControllerTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that image data for enabling 2FA is returned by the endpoint and that the user
     * record in the database is updated as expected.
     */
    public function test_two_factor_image_data_is_returned(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['use_totp' => false]);

        $this->assertFalse($user->use_totp);
        $this->assertEmpty($user->totp_secret);
        $this->assertEmpty($user->totp_authenticated_at);

        $response = $this->actingAs($user)->getJson('/api/client/account/two-factor');

        $response->assertOk();
        $response->assertJsonStructure(['data' => ['image_url_data']]);

        $user = $user->refresh();

        $this->assertFalse($user->use_totp);
        $this->assertNotEmpty($user->totp_secret);
        $this->assertEmpty($user->totp_authenticated_at);
    }

    /**
     * Test that an error is returned if the user's account already has 2FA enabled on it.
     */
    public function test_error_is_returned_when_two_factor_is_already_enabled(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['use_totp' => true]);

        $response = $this->actingAs($user)->getJson('/api/client/account/two-factor');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('errors.0.code', 'BadRequestHttpException');
        $response->assertJsonPath('errors.0.detail', 'Two-factor authentication is already enabled on this account.');
    }

    /**
     * Test that a validation error is thrown if invalid data is passed to the 2FA endpoint.
     */
    public function test_validation_error_is_returned_if_invalid_data_is_passed_to_enabled2_fa(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['use_totp' => false]);

        $this->actingAs($user)
            ->postJson('/api/client/account/two-factor', ['code' => ''])
            ->assertUnprocessable()
            ->assertJsonPath('errors.0.meta.rule', 'required')
            ->assertJsonPath('errors.0.meta.source_field', 'code')
            ->assertJsonPath('errors.1.meta.rule', 'required')
            ->assertJsonPath('errors.1.meta.source_field', 'password');
    }

    /**
     * Tests that 2FA can be enabled on an account for the user.
     */
    public function test_two_factor_can_be_enabled_on_account(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['use_totp' => false]);

        // Make the initial call to get the account setup for 2FA.
        $this->actingAs($user)->getJson('/api/client/account/two-factor')->assertOk();

        $user = $user->refresh();
        $this->assertNotNull($user->totp_secret);

        /** @var \PragmaRX\Google2FA\Google2FA $service */
        $service = $this->app->make(Google2FA::class);

        $token = $service->getCurrentOtp($user->totp_secret);

        $response = $this->actingAs($user)->postJson('/api/client/account/two-factor', [
            'code' => $token,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonPath('object', 'recovery_tokens');

        $user = $user->refresh();
        $this->assertTrue($user->use_totp);

        $tokens = RecoveryToken::query()->where('user_id', $user->id)->get();
        $this->assertCount(10, $tokens);
        $this->assertStringStartsWith('$2y$', $tokens[0]->token);

        // Ensure the recovery tokens that were created include a "created_at" timestamp value on them.
        $this->assertNotNull($tokens[0]->created_at);

        $tokens = $tokens->pluck('token')->toArray();

        $rawTokens = $response->json('attributes.tokens');
        $rawToken = reset($rawTokens);
        $hashed = reset($tokens);

        throw_unless(password_verify($rawToken, $hashed), new ExpectationFailedException(sprintf('Failed asserting that token [%s] exists as a hashed value in recovery_tokens table.', $rawToken)));
    }

    /**
     * Test that two-factor authentication can be disabled on an account as long as the password
     * provided is valid for the account.
     */
    public function test_two_factor_can_be_disabled_on_account(): void
    {
        Carbon::setTestNow(Carbon::now());

        /** @var \App\Models\User $user */
        $user = User::factory()->create(['use_totp' => true]);

        $response = $this->actingAs($user)->deleteJson('/api/client/account/two-factor', [
            'password' => 'invalid',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('errors.0.code', 'BadRequestHttpException');
        $response->assertJsonPath('errors.0.detail', 'The password provided was not valid.');

        $response = $this->actingAs($user)->deleteJson('/api/client/account/two-factor', [
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $user = $user->refresh();
        $this->assertFalse($user->use_totp);
        $this->assertNotNull($user->totp_authenticated_at);
        $this->assertTrue(now()->isSameAs('Y-m-d H:i:s', $user->totp_authenticated_at));
    }

    /**
     * Test that no error is returned when trying to disabled two factor on an account where it
     * was not enabled in the first place.
     */
    public function test_no_error_is_returned_if_two_factor_is_not_enabled(): void
    {
        Carbon::setTestNow(Carbon::now());

        /** @var \App\Models\User $user */
        $user = User::factory()->create(['use_totp' => false]);

        $response = $this->actingAs($user)->deleteJson('/api/client/account/two-factor', [
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * Test that a valid account password is required when enabling two-factor.
     */
    public function test_enabling_two_factor_requires_valid_password(): void
    {
        $user = User::factory()->create(['use_totp' => false]);

        $this->actingAs($user)
            ->postJson('/api/client/account/two-factor', [
                'code' => '123456',
                'password' => 'foo',
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonPath('errors.0.detail', 'The password provided was not valid.');

        $this->assertFalse($user->refresh()->use_totp);
    }

    /**
     * Test that a valid account password is required when disabling two-factor.
     */
    public function test_disabling_two_factor_requires_valid_password(): void
    {
        $user = User::factory()->create(['use_totp' => true]);

        $this->actingAs($user)
            ->deleteJson('/api/client/account/two-factor', [
                'password' => 'foo',
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonPath('errors.0.detail', 'The password provided was not valid.');

        $this->assertTrue($user->refresh()->use_totp);
    }
}
