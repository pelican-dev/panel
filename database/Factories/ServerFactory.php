<?php

namespace Database\Factories;

use App\Models\Allocation;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Server;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ServerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Server::class;

    public function withNode(?Node $node = null): static
    {
        $node ??= Node::factory()->create();

        return $this->state(fn () => [
            'node_id' => $node->id,
            'allocation_id' => Allocation::factory([
                'node_id' => $node->id,
            ]),
        ]);
    }

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'node_id' => Node::factory(),
            'allocation_id' => Allocation::factory(),
            'egg_id' => Egg::factory(),
            'uuid' => Uuid::uuid4()->toString(),
            'uuid_short' => Str::lower(Str::random(8)),
            'name' => $this->faker->firstName(),
            'description' => implode(' ', $this->faker->sentences()),
            'skip_scripts' => 0,
            'status' => null,
            'memory' => 512,
            'swap' => 0,
            'disk' => 512,
            'io' => 500,
            'cpu' => 0,
            'threads' => null,
            'oom_killer' => false,
            'startup' => '/bin/bash echo "hello world"',
            'image' => 'foo/bar:latest',
            'allocation_limit' => null,
            'database_limit' => null,
            'backup_limit' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
