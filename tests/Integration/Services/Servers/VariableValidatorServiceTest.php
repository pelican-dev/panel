<?php

namespace App\Tests\Integration\Services\Servers;

use App\Models\Egg;
use App\Models\User;
use App\Services\Servers\VariableValidatorService;
use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class VariableValidatorServiceTest extends IntegrationTestCase
{
    protected Egg $egg;

    protected function setUp(): void
    {
        parent::setUp();

        /* @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->egg = Egg::query()
            ->where('author', 'panel@example.com')
            ->where('name', 'Bungeecord')
            ->firstOrFail();
    }

    /**
     * Test that environment variables for a server are validated as expected.
     */
    public function test_environment_variables_can_be_validated(): void
    {
        $egg = $this->cloneEggAndVariables($this->egg);

        try {
            $this->getService()->handle($egg->id, [
                'BUNGEE_VERSION' => '1.2.3',
                'SERVER_JARFILE' => '',
            ]);

            $this->fail('This statement should not be reached.');
        } catch (ValidationException $exception) {
            $errors = $exception->errors();

            $this->assertCount(2, $errors);
            $this->assertArrayHasKey('environment.BUNGEE_VERSION', $errors);
            $this->assertArrayHasKey('environment.SERVER_JARFILE', $errors);
            $this->assertSame('The Bungeecord Version variable may only contain letters and numbers.', $errors['environment.BUNGEE_VERSION'][0]);
            $this->assertSame('The Bungeecord Jar File variable field is required.', $errors['environment.SERVER_JARFILE'][0]);
        }

        $response = $this->getService()->handle($egg->id, [
            'BUNGEE_VERSION' => '1234',
            'SERVER_JARFILE' => 'server.jar',
        ]);

        $bungeeVersion = $response->firstWhere('key', 'BUNGEE_VERSION');
        $serverJarfile = $response->firstWhere('key', 'SERVER_JARFILE');

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(2, $response);
        $this->assertSame('BUNGEE_VERSION', $bungeeVersion->key);
        $this->assertSame('1234', $bungeeVersion->value);
        $this->assertSame('SERVER_JARFILE', $serverJarfile->key);
        $this->assertSame('server.jar', $serverJarfile->value);
    }

    /**
     * Test that variables that are user_editable=false do not get validated (or returned) by
     * the handler.
     */
    public function test_normal_user_cannot_validate_non_user_editable_variables(): void
    {
        $egg = $this->cloneEggAndVariables($this->egg);
        $egg->variables()->firstWhere('env_variable', 'BUNGEE_VERSION')->update([
            'user_editable' => false,
        ]);

        $response = $this->getService()->handle($egg->id, [
            // This is an invalid value, but it shouldn't cause any issues since it should be skipped.
            'BUNGEE_VERSION' => '1.2.3',
            'SERVER_JARFILE' => 'server.jar',
        ]);

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(1, $response);
        $this->assertSame('SERVER_JARFILE', $response->firstWhere('key', 'SERVER_JARFILE')->key);
        $this->assertSame('server.jar', $response->firstWhere('key', 'SERVER_JARFILE')->value);
    }

    public function test_environment_variables_can_be_updated_as_admin(): void
    {
        $egg = $this->cloneEggAndVariables($this->egg);
        $egg->variables()->first()->update([
            'user_editable' => false,
        ]);

        try {
            $this->getService()->setUserLevel(User::USER_LEVEL_ADMIN)->handle($egg->id, [
                'BUNGEE_VERSION' => '1.2.3',
                'SERVER_JARFILE' => 'server.jar',
            ]);

            $this->fail('This statement should not be reached.');
        } catch (ValidationException $exception) {
            $this->assertCount(1, $exception->errors());
            $this->assertArrayHasKey('environment.BUNGEE_VERSION', $exception->errors());
        }

        $response = $this->getService()->setUserLevel(User::USER_LEVEL_ADMIN)->handle($egg->id, [
            'BUNGEE_VERSION' => '123',
            'SERVER_JARFILE' => 'server.jar',
        ]);

        $bungeeVersion = $response->firstWhere('key', 'BUNGEE_VERSION');
        $serverJarfile = $response->firstWhere('key', 'SERVER_JARFILE');

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(2, $response);
        $this->assertSame('BUNGEE_VERSION', $bungeeVersion->key);
        $this->assertSame('123', $bungeeVersion->value);
        $this->assertSame('SERVER_JARFILE', $serverJarfile->key);
        $this->assertSame('server.jar', $serverJarfile->value);
    }

    public function test_nullable_environment_variables_can_be_used_correctly(): void
    {
        $egg = $this->cloneEggAndVariables($this->egg);
        $egg->variables()->where('env_variable', '!=', 'BUNGEE_VERSION')->delete();

        $egg->variables()->update(['rules' => ['nullable', 'string']]);

        $response = $this->getService()->handle($egg->id, []);
        $this->assertCount(1, $response);
        $this->assertNull($response->get(0)->value);

        $response = $this->getService()->handle($egg->id, ['BUNGEE_VERSION' => null]);
        $this->assertCount(1, $response);
        $this->assertNull($response->get(0)->value);

        $response = $this->getService()->handle($egg->id, ['BUNGEE_VERSION' => '']);
        $this->assertCount(1, $response);
        $this->assertSame('', $response->get(0)->value);
    }

    private function getService(): VariableValidatorService
    {
        return $this->app->make(VariableValidatorService::class);
    }
}
