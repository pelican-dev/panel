<?php

namespace App\Livewire;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\EditAction;
use App\Models\Setting;
use Filament\Tables\Actions\Action;
use App\Traits\Commands\EnvironmentWriterTrait;
use Illuminate\Support\Facades\Log;
use SQLite3;
use Illuminate\Support\Facades\Artisan;
use App\Filament\Exports\SettingExporter;
use App\Filament\Imports\SettingImporter;
use Filament\Tables\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Actions\ActionGroup;

//use Filament\Forms\Components\Tabs; // TODO 5 PR #259

class Settings extends Component implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable
{
    use EnvironmentWriterTrait;
    use \Filament\Forms\Concerns\InteractsWithForms;
    use \Filament\Tables\Concerns\InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Setting::query())
            //->heading('Settings')
            ->headerActions([
                Action::make('apply')
                    ->label('Apply Settings')
                    ->icon('bi-save-fill')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn () => $this->setSettingsToEnv()),
                ActionGroup::make([
                    ImportAction::make()
                        ->importer(SettingImporter::class),
                    ExportAction::make()
                        ->columnMapping(false)
                        ->exporter(SettingExporter::class),
                ]),
            ])
            ->columns([
                TextColumn::make('label')
                    ->label('Setting')
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn ($record) => $record->description),
                  //  ->action(), TODO 10

                TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(fn ($state) => $state === null ? 'Empty' : $state)
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                EditAction::make()
                    ->form(function ($record) {
                        $settings = new Setting();
                        $setting = collect($settings->getRows())->firstWhere('key', $record->key);

                        return match ($setting['type']) {
                            'select' => [
                                Select::make('value')
                                    ->label($setting['label'])
                                    ->options($setting['options']),
                            ],
                            'number' => [
                                TextInput::make('value')
                                    ->label($setting['label'])
                                    ->placeholder($setting['description'])
                                    ->type('number'),
                            ],
                            'limit' => [
                                TextInput::make('value')
                                    ->label($setting['label'])
                                    ->maxLength($setting['limit'] ?? null)
                                    ->placeholder($setting['description']),
                            ],
                            'password' => [
                                TextInput::make('value')
                                    ->label($setting['label'])
                                    ->password()
                                    ->revealable()
                                    ->placeholder($setting['description']),
                            ],
                            'toggle-buttons' => [
                                ToggleButtons::make('value')
                                    ->inline(true)
                                    ->label($setting['label'])
                                    ->options([
                                        'true' => 'True',
                                        'false' => 'False',
                                    ])
                                    ->colors([
                                        'false' => 'danger',
                                        'true' => 'success',
                                    ]),
                            ],
                            default => [
                                TextInput::make('value')
                                    ->label($setting['label'])
                                    ->placeholder($setting['description']),
                            ],
                        };
                    }),
            ]);
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            {{ $this->table }}
        </div>
        HTML;
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
