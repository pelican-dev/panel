<?php

namespace App\Tests\Integration\Services\Servers;

use App\Models\Mount;
use App\Models\ServerVariable;
use App\Services\Servers\ServerCloneService;
use App\Tests\Integration\IntegrationTestCase;
use Ramsey\Uuid\Uuid;

class ServerCloneServiceTest extends IntegrationTestCase
{
    /**
     * Test that the configuration of a server is copied over.
     */
    public function test_server_configuration_is_copied(): void
    {
        $server = $this->createServerModel([
            'name' => 'test-server',
            'description' => 'A server to clone',
            'memory' => 1024,
            'disk' => 2048,
            'cpu' => 150,
            'swap' => 256,
            'io' => 250,
            'threads' => '0,2-4',
            'oom_killer' => true,
            'allocation_limit' => 3,
            'database_limit' => 2,
            'backup_limit' => 1,
            'docker_labels' => ['foo' => 'bar'],
            'skip_scripts' => true,
        ]);

        $data = $this->getService()->handle($server);

        $this->assertSame('test-server (Copy)', $data['name']);
        $this->assertSame('A server to clone', $data['description']);
        $this->assertSame($server->node_id, $data['node_id']);
        $this->assertSame($server->owner_id, $data['owner_id']);
        $this->assertSame($server->egg_id, $data['egg_id']);
        $this->assertSame($server->startup, $data['startup']);
        $this->assertSame($server->image, $data['image']);
        $this->assertSame(1024, $data['memory']);
        $this->assertSame(2048, $data['disk']);
        $this->assertSame(150, $data['cpu']);
        $this->assertSame(256, $data['swap']);
        $this->assertSame(250, $data['io']);
        $this->assertSame('0,2-4', $data['threads']);
        $this->assertSame(1, $data['oom_killer']);
        $this->assertSame(3, $data['allocation_limit']);
        $this->assertSame(2, $data['database_limit']);
        $this->assertSame(1, $data['backup_limit']);
        $this->assertSame(['foo' => 'bar'], $data['docker_labels']);
        $this->assertSame(1, $data['skip_scripts']);
    }

    /**
     * Test that everything that has to be unique for a server is left out.
     */
    public function test_unique_server_details_are_not_copied(): void
    {
        $server = $this->createServerModel(['external_id' => 'external-1']);

        $data = $this->getService()->handle($server);

        $this->assertNull($data['external_id']);
        $this->assertNull($data['allocation_id']);
        $this->assertSame([], $data['allocation_additional']);
    }

    /**
     * Test that limits are mapped onto the toggles used by the creation form.
     */
    public function test_limits_are_mapped_onto_the_form_toggles(): void
    {
        $limited = $this->createServerModel([
            'cpu' => 100,
            'memory' => 512,
            'disk' => 512,
            'swap' => 128,
            'threads' => '0-2',
        ]);

        $data = $this->getService()->handle($limited);

        $this->assertSame(0, $data['unlimited_cpu']);
        $this->assertSame(0, $data['unlimited_mem']);
        $this->assertSame(0, $data['unlimited_disk']);
        $this->assertSame('limited', $data['swap_support']);
        $this->assertSame(1, $data['cpu_pinning']);

        $unlimited = $this->createServerModel([
            'cpu' => 0,
            'memory' => 0,
            'disk' => 0,
            'swap' => -1,
            'threads' => null,
        ]);

        $data = $this->getService()->handle($unlimited);

        $this->assertSame(1, $data['unlimited_cpu']);
        $this->assertSame(1, $data['unlimited_mem']);
        $this->assertSame(1, $data['unlimited_disk']);
        $this->assertSame('unlimited', $data['swap_support']);
        $this->assertSame(0, $data['cpu_pinning']);

        $disabledSwap = $this->createServerModel(['swap' => 0]);

        $this->assertSame('disabled', $this->getService()->handle($disabledSwap)['swap_support']);
    }

    /**
     * Test that the egg variables of a server keep their value.
     */
    public function test_variable_values_are_copied(): void
    {
        $server = $this->createServerModel();
        $variable = $server->egg->variables->first();

        ServerVariable::query()->create([
            'server_id' => $server->id,
            'variable_id' => $variable->id,
            'variable_value' => 'cloned-value',
        ]);

        $data = $this->getService()->handle($server->refresh());

        $this->assertSame('cloned-value', $data['environment'][$variable->env_variable]);

        $variables = collect($data['server_variables'])->firstWhere('variable_id', $variable->id);

        $this->assertSame('cloned-value', $variables['variable_value']);
        $this->assertSame($variable->env_variable, $variables['env_variable']);
    }

    /**
     * Test that a variable without a value falls back to the default of the egg.
     */
    public function test_variables_without_a_value_use_the_egg_default(): void
    {
        $server = $this->createServerModel();
        $variable = $server->egg->variables->first();

        $data = $this->getService()->handle($server);

        $this->assertSame($variable->default_value, $data['environment'][$variable->env_variable]);
    }

    /**
     * Test that a startup command or image that is not part of the egg is marked as custom.
     */
    public function test_custom_startup_and_image_are_detected(): void
    {
        $server = $this->createServerModel([
            'startup' => 'java -jar custom.jar',
            'image' => 'ghcr.io/example/image:latest',
        ]);

        $data = $this->getService()->handle($server);

        $this->assertSame('custom', $data['select_startup']);
        $this->assertSame('ghcr.io/custom-image', $data['select_image']);
    }

    /**
     * Test that a startup command or image of the egg is pre-selected.
     */
    public function test_startup_and_image_of_the_egg_are_selected(): void
    {
        $server = $this->createServerModel();

        $server->update([
            'startup' => collect($server->egg->startup_commands)->first(),
            'image' => collect($server->egg->docker_images)->first(),
        ]);

        $data = $this->getService()->handle($server->refresh());

        $this->assertSame($server->startup, $data['select_startup']);
        $this->assertSame($server->image, $data['select_image']);
    }

    /**
     * Test that the mounts of a server are copied.
     */
    public function test_mounts_are_copied(): void
    {
        $server = $this->createServerModel();

        $mount = Mount::query()->create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'test-mount',
            'source' => '/mnt/source',
            'target' => '/mnt/target',
            'read_only' => false,
            'user_mountable' => false,
        ]);

        $server->mounts()->attach($mount);

        $data = $this->getService()->handle($server->refresh());

        $this->assertSame([$mount->id], $data['mounts']);
    }

    private function getService(): ServerCloneService
    {
        return $this->app->make(ServerCloneService::class);
    }
}
