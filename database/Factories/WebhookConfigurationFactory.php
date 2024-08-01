<?php

namespace Database\Factories;

use App\Models\WebhookConfiguration;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebhookConfigurationFactory extends Factory
{
    protected $model = WebhookConfiguration::class;

    public function definition(): array
    {
        return [
            'endpoint' => fake()->url(),
            'description' => fake()->sentence(),
            'events' => [],
        ];
    }
}
