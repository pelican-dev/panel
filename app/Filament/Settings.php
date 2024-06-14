<?php

namespace App\Filament;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Setting;
use App\Traits\Commands\EnvironmentWriterTrait;
use Illuminate\Support\Facades\Artisan;

class Settings extends Page
{
    use EnvironmentWriterTrait;

    protected static ?string $navigationIcon = 'tabler-settings';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 12;

    public function getTitle(): string
    {
        return trans('strings.settings');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('apply')
                ->label('Apply Settings')
                ->requiresConfirmation()
                ->action(fn () => $this->setConfigFromDatabase()),
        ];
    }

    protected function setConfigFromDatabase(): void
    {
        $settings = new Setting();
        $rows = $settings->getRows();

        $variables = [];

        foreach ($rows as $setting) {
            $variables[$setting['key']] = $setting['value'];
        }

        $this->writeToEnvironment($variables);
        //Artisan::call('config:cache');
    }

}
