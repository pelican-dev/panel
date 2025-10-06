<?php

namespace Database\Factories;

use App\Models\Database;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DatabaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Database::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        static $password;

        return [
            'database' => Str::random(10),
            'username' => Str::random(10),
            'remote' => '%',
            'password' => $password ?: 'test123',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
