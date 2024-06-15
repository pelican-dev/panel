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
use Filament\Forms\Components\Tabs; // TODO 5 PR #259

class Settings extends Component implements \Filament\Forms\Contracts\HasForms, \Filament\Tables\Contracts\HasTable
{
    use \Filament\Forms\Concerns\InteractsWithForms;
    use \Filament\Tables\Concerns\InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Setting::query())
            ->columns([
                TextColumn::make('label')
                    ->label('Setting')
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn ($record) => $record->description)
                    ->action(fn ($record) => $this->editSetting($record->id)),

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
                            'toggle-buttons' => [
                                ToggleButtons::make('value')
                                    ->inline(false)
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

    public function editSetting($id)
    {
        $this->dispatchFormEvent('trigger-edit', ['id' => $id]);
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            {{ $this->table }}
        </div>
        HTML;
    }
}
