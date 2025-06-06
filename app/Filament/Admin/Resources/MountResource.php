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
use Illuminate\Database\Eloquent\Builder;

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
        return (string) static::getEloquentQuery()->count() ?: null;
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
                    ->label(trans('admin/mount.table.name'))
                    ->description(fn (Mount $mount) => "$mount->source -> $mount->target")
                    ->sortable(),
                TextColumn::make('eggs.name')
                    ->icon('tabler-eggs')
                    ->label(trans('admin/mount.eggs'))
                    ->badge()
                    ->placeholder(trans('admin/mount.table.all_eggs')),
                TextColumn::make('nodes.name')
                    ->icon('tabler-server-2')
                    ->label(trans('admin/mount.nodes'))
                    ->badge()
                    ->placeholder(trans('admin/mount.table.all_nodes')),
                TextColumn::make('read_only')
                    ->label(trans('admin/mount.table.read_only'))
                    ->badge()
                    ->icon(fn ($state) => $state ? 'tabler-writing-off' : 'tabler-writing')
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state ? trans('admin/mount.toggles.read_only') : trans('admin/mount.toggles.writable')),
            ])
            ->actions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->emptyStateIcon('tabler-layers-linked')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/mount.no_mounts'))
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->label(trans('admin/mount.name'))
                        ->required()
                        ->helperText(trans('admin/mount.name_help'))
                        ->maxLength(64),
                    ToggleButtons::make('read_only')
                        ->label(trans('admin/mount.read_only'))
                        ->helperText(trans('admin/mount.read_only_help'))
                        ->options([
                            false => trans('admin/mount.toggles.writable'),
                            true => trans('admin/mount.toggles.read_only'),
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
                        ->label(trans('admin/mount.source'))
                        ->required()
                        ->helperText(trans('admin/mount.source_help'))
                        ->maxLength(255),
                    TextInput::make('target')
                        ->label(trans('admin/mount.target'))
                        ->required()
                        ->helperText(trans('admin/mount.target_help'))
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label(trans('admin/mount.description'))
                        ->helperText(trans('admin/mount.description_help'))
                        ->columnSpanFull(),
                ])->columnSpan(1)->columns([
                    'default' => 1,
                    'lg' => 2,
                ]),
                Group::make()->schema([
                    Section::make()->schema([
                        Select::make('eggs')->multiple()
                            ->label(trans('admin/mount.eggs'))
                            ->relationship('eggs', 'name')
                            ->preload(),
                        Select::make('nodes')->multiple()
                            ->label(trans('admin/mount.nodes'))
                            ->relationship('nodes', 'name', fn (Builder $query) => $query->whereIn('nodes.id', auth()->user()->accessibleNodes()->pluck('id')))
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->where(function (Builder $query) {
            return $query->whereHas('nodes', function (Builder $query) {
                $query->whereIn('nodes.id', auth()->user()->accessibleNodes()->pluck('id'));
            })->orDoesntHave('nodes');
        });
    }
}
