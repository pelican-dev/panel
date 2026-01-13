<?php

namespace App\Tests\Integration\Services\Servers;

use App\Models\Egg;
use App\Models\Server;
use App\Models\ServerVariable;
use App\Models\User;
use App\Services\Servers\StartupModificationService;
use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class StartupModificationServiceTest extends IntegrationTestCase
{
    /**
     * Test that a non-admin request to modify the server startup parameters does
     * not perform any egg updates. This also attempts to pass through an
     * egg_id variable which should have no impact if the request is coming from
     * a non-admin entity.
     */
    public function test_non_admin_can_modify_server_variables(): void
    {
        $server = $this->createServerModel();

        try {
            $this->app->make(StartupModificationService::class)->handle($server, [
                'egg_id' => $server->egg_id + 1,
                'environment' => [
                    'BUNGEE_VERSION' => '$$',
                    'SERVER_JARFILE' => 'server.jar',
                ],
            ]);

            $this->fail('This assertion should not be called.');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(ValidationException::class, $exception);

            /** @var \Illuminate\Validation\ValidationException $exception */
            $errors = $exception->validator->errors()->toArray();

            $this->assertCount(1, $errors);
            $this->assertArrayHasKey('environment.BUNGEE_VERSION', $errors);
            $this->assertCount(1, $errors['environment.BUNGEE_VERSION']);
            $this->assertSame('The Bungeecord Version variable may only contain letters and numbers.', $errors['environment.BUNGEE_VERSION'][0]);
        }

        ServerVariable::query()->where('variable_id', $server->variables[1]->id)->delete();

        $result = $this->getService()
            ->handle($server, [
                'egg_id' => $server->egg_id + 1,
                'startup' => 'random gibberish',
                'environment' => [
                    'BUNGEE_VERSION' => '1234',
                    'SERVER_JARFILE' => 'test.jar',
                ],
            ]);

        $this->assertInstanceOf(Server::class, $result);
        $this->assertCount(2, $result->variables);
        $this->assertSame($server->startup, $result->startup);
        $this->assertSame('1234', $result->variables->firstWhere('env_variable', 'BUNGEE_VERSION')->server_value);
        $this->assertSame('test.jar', $result->variables->firstWhere('env_variable', 'SERVER_JARFILE')->server_value);
    }

    /**
     * Test that modifying an egg as an admin properly updates the data for the server.
     */
    public function test_server_is_properly_modified_as_admin_user(): void
    {
        /** @var \App\Models\Egg $nextEgg */
        $nextEgg = Egg::query()->findOrFail(2);

        $server = $this->createServerModel(['egg_id' => 1]);

        $this->assertNotSame($nextEgg->id, $server->egg_id);

        $response = $this->getService()
            ->setUserLevel(User::USER_LEVEL_ADMIN)
            ->handle($server, [
                'egg_id' => $nextEgg->id,
                'startup' => 'sample startup',
                'skip_scripts' => true,
                'docker_image' => 'docker/hodor',
            ]);

        $this->assertInstanceOf(Server::class, $response);
        $this->assertSame($nextEgg->id, $response->egg_id);
        $this->assertSame('sample startup', $response->startup);
        $this->assertSame('docker/hodor', $response->image);
        $this->assertTrue($response->skip_scripts);
        // Make sure we don't revert back to a lurking bug that causes servers to get marked
        // as not installed when you modify the startup...
        $this->assertTrue($response->isInstalled());
    }

    /**
     * Test that hidden variables can be updated by an admin but are not affected by a
     * regular user who attempts to pass them through.
     */
    public function test_environment_variables_can_be_updated_by_admin(): void
    {
        $server = $this->createServerModel();
        $server->loadMissing(['egg', 'variables']);

        $clone = $this->cloneEggAndVariables($server->egg);
        // This makes the BUNGEE_VERSION variable not user editable.
        $clone->variables()->firstWhere('env_variable', 'BUNGEE_VERSION')->update([
            'user_editable' => false,
        ]);

        $server->fill(['egg_id' => $clone->id])->saveOrFail();
        $server->refresh();

        ServerVariable::query()->updateOrCreate([
            'server_id' => $server->id,
            'variable_id' => $server->variables()->firstWhere('env_variable', 'BUNGEE_VERSION')->id,
        ], ['variable_value' => 'EXIST']);

        $response = $this->getService()->handle($server, [
            'environment' => [
                'BUNGEE_VERSION' => '1234',
                'SERVER_JARFILE' => 'test.jar',
            ],
        ]);

        $this->assertCount(2, $response->variables);
        $this->assertSame('EXIST', $response->variables()->firstWhere('env_variable', 'BUNGEE_VERSION')->server_value);
        $this->assertSame('test.jar', $response->variables()->firstWhere('env_variable', 'SERVER_JARFILE')->server_value);

        $response = $this->getService()
            ->setUserLevel(User::USER_LEVEL_ADMIN)
            ->handle($server, [
                'environment' => [
                    'BUNGEE_VERSION' => '1234',
                    'SERVER_JARFILE' => 'test.jar',
                ],
            ]);

        $this->assertCount(2, $response->variables);
        $this->assertSame('1234', $response->variables()->firstWhere('env_variable', 'BUNGEE_VERSION')->server_value);
        $this->assertSame('test.jar', $response->variables()->firstWhere('env_variable', 'SERVER_JARFILE')->server_value);
    }

    /**
     * Test that passing an invalid egg ID into the function throws an exception
     * rather than silently failing or skipping.
     */
    public function test_invalid_egg_id_triggers_exception(): void
    {
        $server = $this->createServerModel();

        $this->expectException(ModelNotFoundException::class);

        $this->getService()
            ->setUserLevel(User::USER_LEVEL_ADMIN)
            ->handle($server, ['egg_id' => 123456789]);
    }

    private function getService(): StartupModificationService
    {
        return $this->app->make(StartupModificationService::class);
    }
}
