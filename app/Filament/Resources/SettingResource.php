<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput as LimitInput;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'tabler-settings';

    protected static ?int $navigationSort = 23;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->groups([
                Group::make('group')
                    ->collapsible(),
            ])
            ->groupingSettingsHidden(false)
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Setting')
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn ($record) => $record->description),

                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(fn ($state) => $state === null ? 'Empty' : $state)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last update on')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(function (Setting $record) {
                        return match ($record->type) {
                            'select' => [
                                Select::make('value')
                                    ->label($record->label)
                                    ->options($record->attributes['options']),
                            ],
                            'number' => [
                                TextInput::make('value')
                                    ->label($record->label)
                                    ->placeholder($record->description)
                                    ->type('number'),
                            ],
                            'limit' => [
                                LimitInput::make('value')
                                    ->label($record->label)
                                    ->maxLength($record->limit)
                                    ->showInsideControl(false)
                                    ->placeholder($record->description),
                            ],
                            default => [
                                TextInput::make('value')
                                    ->label($record->label)
                                    ->placeholder($record->description),
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
