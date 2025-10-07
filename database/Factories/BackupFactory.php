<?php

namespace Database\Factories;

use App\Models\Backup;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class BackupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Backup::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $this->faker->sentence(),
            'disk' => Backup::ADAPTER_DAEMON,
            'is_successful' => true,
            'created_at' => CarbonImmutable::now(),
            'completed_at' => CarbonImmutable::now(),
            'ignored_files' => [],
        ];
    }
}
