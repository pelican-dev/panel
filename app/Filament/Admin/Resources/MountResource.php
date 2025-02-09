<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MountResource\Pages;
use App\Models\Mount;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MountResource extends Resource
{
    protected static ?string $model = Mount::class;

    protected static ?string $navigationIcon = 'tabler-layers-linked';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return trans('admin/mount.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/mount.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/mount.model_label_plural');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->description(fn (Mount $mount) => "$mount->source -> $mount->target")
                    ->sortable(),
                TextColumn::make('eggs.name')
                    ->icon('tabler-eggs')
                    ->label('Eggs')
                    ->badge()
                    ->placeholder('All eggs'),
                TextColumn::make('nodes.name')
                    ->icon('tabler-server-2')
                    ->label('Nodes')
                    ->badge()
                    ->placeholder('All nodes'),
                TextColumn::make('read_only')
                    ->label('Read only?')
                    ->badge()
                    ->icon(fn ($state) => $state ? 'tabler-writing-off' : 'tabler-writing')
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state ? 'Read only' : 'Writeable'),
            ])
            ->actions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->authorize(fn () => auth()->user()->can('delete mount')),
            ])
            ->emptyStateIcon('tabler-layers-linked')
            ->emptyStateDescription('')
            ->emptyStateHeading('No Mounts')
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Create Mount')
                    ->button(),
            ]);
    }

    public static function form(Form $form): Form
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMounts::route('/'),
            'create' => Pages\CreateMount::route('/create'),
            'view' => Pages\ViewMount::route('/{record}'),
            'edit' => Pages\EditMount::route('/{record}/edit'),
        ];
    }
}
