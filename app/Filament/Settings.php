<?php

namespace App\Filament;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Traits\Commands\EnvironmentWriterTrait;
use Illuminate\Support\Facades\Log;
use SQLite3;
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
                ->action(fn () => $this->setSettingsToEnv()),
        ];
    }

    protected function setSettingsToEnv(): void
    {
        try {
            $sqliteFile = storage_path('framework/cache/sushi-app-models-setting.sqlite');

            $sqlite = new SQLite3($sqliteFile);

            $query = 'SELECT * FROM `settings`';

            $result = $sqlite->query($query);

            $variables = [];

            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $variables[$row['key']] = $row['value'];

                Log::info('Setting applied to .env file: ' . $row['key'] . ' => ' . $row['value']);
            }

            $sqlite->close();

            $this->writeToEnvironment($variables);
            Artisan::call('config:cache');

            Log::info('All settings applied successfully to .env file.');
        } catch (\Exception $e) {
            Log::error('Error applying settings to .env file: ' . $e->getMessage());
        }
    }
}
