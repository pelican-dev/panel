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

class Settings extends Component implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable
{
    use EnvironmentWriterTrait;
    use \Filament\Forms\Concerns\InteractsWithForms;
    use \Filament\Tables\Concerns\InteractsWithTable;

    private $settings;

    public function __construct()
    {
        $this->settings = Settings::all();
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Setting::query())
            //->heading('Settings')
            ->headerActions([
                Action::make('apply')
                    ->label('Apply Settings')
                    ->icon('tabler-device-floppy')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn () => $this->setSettingsToEnv()),
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
                    ->using(function (Setting $setting, array $data): Setting {
                        $setting->writeToEnvironment([$setting->key => $data['value']]);

                        return $setting;
                    })
                    ->form(function ($record) {
                        $setting = Setting::all()->firstWhere('key', $record->key);

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
        dd($this->settings);

        $this->writeToEnvironment($variables);
    }
}
