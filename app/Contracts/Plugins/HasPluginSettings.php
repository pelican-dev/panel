<?php

namespace App\Contracts\Plugins;

interface HasPluginSettings
{
    public function getSettingsForm(): array;

    public function saveSettings(array $data): void;
}
