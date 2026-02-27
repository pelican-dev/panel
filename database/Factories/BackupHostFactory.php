<?php

namespace Database\Factories;

use App\Models\BackupHost;
use Illuminate\Database\Eloquent\Factories\Factory;

class BackupHostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BackupHost::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->colorName(),
            'schema' => 'wings',
            'configuration' => null,
        ];
    }
}
