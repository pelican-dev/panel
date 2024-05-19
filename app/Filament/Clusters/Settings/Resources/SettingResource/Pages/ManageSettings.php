<?php

namespace App\Filament\Clusters\Settings\Resources\SettingResource\Pages;

use App\Filament\Clusters\Settings\Resources\SettingResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Illuminate\Database\QueryException;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class ManageSettings extends ManageRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('apply')
                ->label('Apply Settings')
                ->requiresConfirmation()
                ->action($this->setConfigFromDatabase()),
        ];
    }

    protected function setConfigFromDatabase(): void
    {
        try {
            $settings = Setting::pluck('value', 'key')->toArray();
            \Log::info('Settings retrieved from database:', $settings);
        } catch (QueryException $exception) {
            \Log::error('Error retrieving settings from the database: ' . $exception->getMessage());

            return;
        }

        foreach ($settings as $key => $value) {
            Config::set($key, $value);
            Cache::flush();
        }
    }
}
