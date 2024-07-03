<?php

namespace App\Filament\Resources\MountResource\Pages;

use App\Filament\Resources\MountResource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateMount extends CreateRecord
{
    protected static string $resource = MountResource::class;

    protected static bool $canCreateAnother = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->helperText('Unique name used to separate this mount from another.')
                        ->maxLength(64),
                    ToggleButtons::make('read_only')
                        ->label('Read only?')
                        ->helperText('Is the mount read only inside the container?')
                        ->options([
                            false => 'Writeable',
                            true => 'Read only',
                        ])
                        ->icons([
                            false => 'tabler-writing',
                            true => 'tabler-writing-off',
                        ])
                        ->colors([
                            false => 'warning',
                            true => 'success',
                        ])
                        ->inline()
                        ->default(false)
                        ->required(),
                    TextInput::make('source')
                        ->required()
                        ->helperText('File path on the host system to mount to a container.')
                        ->maxLength(255),
                    TextInput::make('target')
                        ->required()
                        ->helperText('Where the mount will be accessible inside a container.')
                        ->maxLength(255),
                    ToggleButtons::make('user_mountable')
                        ->hidden()
                        ->label('User mountable?')
                        ->options([
                            false => 'No',
                            true => 'Yes',
                        ])
                        ->icons([
                            false => 'tabler-user-cancel',
                            true => 'tabler-user-bolt',
                        ])
                        ->colors([
                            false => 'success',
                            true => 'warning',
                        ])
                        ->default(false)
                        ->inline()
                        ->required(),
                    Textarea::make('description')
                        ->helperText('A longer description for this mount.')
                        ->columnSpanFull(),
                    Hidden::make('user_mountable')->default(1),
                ])->columnSpan(1)->columns([
                    'default' => 1,
                    'lg' => 2,
                ]),
                Group::make()->schema([
                    Section::make()->schema([
                        Select::make('eggs')->multiple()
                            ->relationship('eggs', 'name')
                            ->preload(),
                        Select::make('nodes')->multiple()
                            ->relationship('nodes', 'name')
                            ->searchable(['name', 'fqdn'])
                            ->preload(),
                    ]),
                ])->columns([
                    'default' => 1,
                    'lg' => 2,
                ]),
            ])->columns([
                'default' => 1,
                'lg' => 2,
            ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['uuid'] ??= Str::uuid()->toString();

        return parent::handleRecordCreation($data);
    }
}
