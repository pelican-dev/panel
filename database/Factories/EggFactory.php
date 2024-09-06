<?php

namespace Database\Factories;

use Ramsey\Uuid\Uuid;
use App\Models\Egg;
use Illuminate\Database\Eloquent\Factories\Factory;

class EggFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Egg::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'author' => $this->faker->email(),
            'docker_images' => ['a', 'b', 'c'],
            'config_logs' => '{}',
            'config_startup' => '{}',
            'config_stop' => '{}',
            'config_files' => '{}',
            'name' => $this->faker->name(),
            'description' => implode(' ', $this->faker->sentences()),
            'startup' => 'java -jar test.jar',
        ];
    }
}
