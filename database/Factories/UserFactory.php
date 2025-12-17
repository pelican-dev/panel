<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        static $password;

        return [
            'external_id' => null,
            'is_managed_externally' => false,
            'uuid' => Uuid::uuid4()->toString(),
            'username' => $this->faker->userName() . '_' . Str::random(10),
            'email' => Str::random(32) . '@example.com',
            'password' => $password ?: $password = bcrypt('password'),
            'language' => 'en',
            'oauth' => [],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
