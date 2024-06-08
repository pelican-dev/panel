<?php

namespace App\Filament\Clusters\Settings\Resources\SettingResource\Pages;

use App\Filament\Clusters\Settings\Resources\SettingResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Illuminate\Database\QueryException;
use App\Models\Setting;
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
        } catch (QueryException $exception) {
            return;
        }

        foreach ($settings as $key => $value) {
            config()->set($key, $value);
            Cache::put('key', 'value');
        }
    }
}
