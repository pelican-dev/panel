<?php

namespace Database\Factories;

use App\Models\ServerTransfer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerTransferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServerTransfer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'old_additional_allocations' => [],
            'new_additional_allocations' => [],
            'successful' => null,
            'archived' => false,
        ];
    }
}
