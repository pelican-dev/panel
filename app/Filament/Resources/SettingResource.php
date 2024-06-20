<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Tables\Actions\EditAction;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'tabler-settings';

    public static function canCreate(): bool
    {
        return false;
    }

    protected static ?int $navigationSort = 23;

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->searchable(false)
            ->query(Setting::query())
            ->striped(false)
            //->heading('Settings')
            ->columns([
                TextColumn::make('label')
                    ->label('Setting')
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn ($record) => $record->description),

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
                    ->form(function (Setting $setting) {
                        return match ($setting->type) {
                            'select' => [
                                Select::make('value')
                                    ->label($setting->label)
                                    ->options($setting->options),
                            ],
                            'number' => [
                                TextInput::make('value')
                                    ->label($setting->label)
                                    ->placeholder($setting->description)
                                    ->type('number'),
                            ],
                            'limit' => [
                                TextInput::make('value')
                                    ->label($setting->label)
                                    ->maxLength($setting->limit)
                                    ->placeholder($setting->description),
                            ],
                            'password' => [
                                TextInput::make('value')
                                    ->label($setting->label)
                                    ->password()
                                    ->revealable()
                                    ->placeholder($setting->description),
                            ],
                            'toggle-buttons' => [
                                ToggleButtons::make('value')
                                    ->inline(true)
                                    ->label($setting->label)
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
                                    ->label($setting->label)
                                    ->placeholder($setting->description),
                            ],
                        };
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSettings::route('/'),
        ];
    }
}
