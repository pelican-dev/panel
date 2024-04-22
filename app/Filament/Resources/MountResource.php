<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MountResource\Pages;
use App\Models\Mount;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MountResource extends Resource
{
    protected static ?string $model = Mount::class;

    public static function getLabel(): string
    {
        return trans_choice('strings.mounts', 1);
    }

    protected static ?string $navigationIcon = 'tabler-layers-linked';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(trans('strings.name'))
                        ->required()
                        ->helperText('Unique name used to separate this mount from another.')
                        ->maxLength(64),
                    Forms\Components\ToggleButtons::make('read_only')
                        ->label(trans('strings.read_only?'))
                        ->helperText('Is the mount read only inside the container?')
                        ->options([
                            false => trans('strings.writable'),
                            true => trans('strings.read_only'),
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
                    Forms\Components\TextInput::make('source')
                        ->required()
                        ->helperText('File path on the host system to mount to a container.')
                        ->maxLength(191),
                    Forms\Components\TextInput::make('target')
                        ->required()
                        ->helperText('Where the mount will be accessible inside a container.')
                        ->maxLength(191),
                    Forms\Components\ToggleButtons::make('user_mountable')
                        ->hidden()
                        ->label('User mountable?')
                        ->options([
                            false => trans('strings.no'),
                            true => trans('strings.yes'),
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
                    Forms\Components\Textarea::make('description')
                        ->label(trans('strings.description'))
                        ->helperText('A longer description for this mount.')
                        ->columnSpanFull(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('strings.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target')
                    ->searchable(),
                Tables\Columns\IconColumn::make('read_only')
                    ->label(trans('strings.read_only'))
                    ->icon(fn (bool $state) => $state ? 'tabler-circle-check-filled' : 'tabler-circle-x-filled')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('user_mountable')
                    ->hidden()
                    ->icon(fn (bool $state) => $state ? 'tabler-circle-check-filled' : 'tabler-circle-x-filled')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMounts::route('/'),
            'create' => Pages\CreateMount::route('/create'),
            'edit' => Pages\EditMount::route('/{record}/edit'),
        ];
    }
}
