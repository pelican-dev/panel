<?php

namespace Database\Factories;

use App\Enums\ServerUserSettingKey;
use App\Models\ServerUserSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerUserSettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServerUserSettings::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'settings' => [
                ServerUserSettingKey::BackupNotifications->value => true,
            ],
        ];
    }

    /**
     * Indicate the user has opted in to backup notifications.
     */
    public function optedIn(): static
    {
        return $this->state([
            'settings' => [
                ServerUserSettingKey::BackupNotifications->value => true,
            ],
        ]);
    }

    /**
     * Indicate the user has opted out of backup notifications.
     */
    public function optedOut(): static
    {
        return $this->state([
            'settings' => [
                ServerUserSettingKey::BackupNotifications->value => false,
            ],
        ]);
    }
}
