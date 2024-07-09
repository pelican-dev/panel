<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use App\Traits\Commands\EnvironmentWriterTrait;

class SettingResource extends Resource
{
    use EnvironmentWriterTrait;

    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'tabler-settings';

    protected static ?string $navigationGroup = 'Advanced';

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
            ->striped(false)
            ->columns([
                TextColumn::make('label')
                    ->label('Setting')
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn ($record) => $record->description ?? 'No description available'),

                TextInputColumn::make('value')
                    ->label('Value')
                    ->default(fn (Setting $setting) => config($setting->config))
                    ->sortable()
                    ->searchable()
                    ->afterStateUpdated(function (Setting $setting, $state) {
                        $setting->value = $state;
                        $setting->writeToEnvironment([$setting->key => $state]);
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
