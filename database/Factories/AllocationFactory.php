<?php

namespace Database\Factories;

use App\Models\Allocation;
use App\Models\Node;
use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

class AllocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Allocation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'ip' => $this->faker->unique()->ipv4(),
            'port' => $this->faker->unique()->numberBetween(1024, 65535),
            'node_id' => Node::factory(),
        ];
    }

    /**
     * Attaches the allocation to a specific server model.
     */
    public function forServer(Server $server): self
    {
        return $this->for($server)->for($server->node);
    }
}
