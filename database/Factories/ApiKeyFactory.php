<?php

namespace Database\Factories;

use App\Models\ApiKey;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApiKeyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApiKey::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        static $token;

        return [
            'key_type' => ApiKey::TYPE_APPLICATION,
            'identifier' => ApiKey::generateTokenIdentifier(ApiKey::TYPE_APPLICATION),
            'token' => $token ?: $token = Str::random(ApiKey::KEY_LENGTH),
            'allowed_ips' => [],
            'permissions' => [],
            'memo' => 'Test Function Key',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
