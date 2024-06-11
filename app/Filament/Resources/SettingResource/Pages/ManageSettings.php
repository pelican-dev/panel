<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Illuminate\Database\QueryException;
use App\Models\Setting;
use App\Traits\Commands\EnvironmentWriterTrait;
use Illuminate\Support\Facades\Artisan;

class ManageSettings extends ManageRecords
{
    use EnvironmentWriterTrait;

    protected static string $resource = SettingResource::class;

    protected array $variables = [];

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
            $this->variables[$key] = $value;
        }

        $this->writeToEnvironment($this->variables);
        //Artisan::call('config:cache'); // When this is called the page expires, but it is the only way to make it work
    }

}
