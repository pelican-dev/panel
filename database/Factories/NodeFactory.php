<?php

namespace Database\Factories;

use App\Models\Node;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class NodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Node::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'public' => true,
            'name' => 'FactoryNode_' . Str::random(10),
            'fqdn' => $this->faker->unique()->ipv4(),
            'scheme' => 'http',
            'behind_proxy' => false,
            'memory' => 1024,
            'memory_overallocate' => 0,
            'disk' => 10240,
            'disk_overallocate' => 0,
            'cpu' => 100,
            'cpu_overallocate' => 0,
            'upload_size' => 100,
            'daemon_token_id' => Str::random(Node::DAEMON_TOKEN_ID_LENGTH),
            'daemon_token' => Str::random(Node::DAEMON_TOKEN_LENGTH),
            'daemon_listen' => 8080,
            'daemon_connect' => 8080,
            'daemon_sftp' => 2022,
            'daemon_base' => '/var/lib/panel/volumes',
            'maintenance_mode' => false,
        ];
    }
}
