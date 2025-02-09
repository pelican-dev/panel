<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ApiKeyResource\Pages;
use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Models\ApiKey;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;

    protected static ?string $navigationIcon = 'tabler-key';

    public static function getNavigationLabel(): string
    {
        return trans('admin/apikey.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/apikey.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/apikey.model_label_plural');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count() ?: null;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->where('key_type', ApiKey::TYPE_APPLICATION);
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->icon('tabler-clipboard-text')
                    ->state(fn (ApiKey $key) => $key->identifier . $key->token)
                    ->copyable(),
                TextColumn::make('memo')
                    ->label('Description')
                    ->wrap()
                    ->limit(50),
                DateTimeColumn::make('last_used_at')
                    ->label('Last Used')
                    ->placeholder('Not Used')
                    ->sortable(),
                DateTimeColumn::make('created_at')
                    ->label('Created')
                    ->sortable(),
                TextColumn::make('user.username')
                    ->label('Created By')
                    ->icon('tabler-user')
                    ->url(fn (ApiKey $apiKey) => auth()->user()->can('update user', $apiKey->user) ? EditUser::getUrl(['record' => $apiKey->user]) : null),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->emptyStateIcon('tabler-key')
            ->emptyStateDescription('')
            ->emptyStateHeading('No API Keys')
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Create API Key')
                    ->button(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Permissions')
                    ->columns([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->schema(
                        collect(ApiKey::getPermissionList())->map(fn ($resource) => ToggleButtons::make('permissions_' . $resource)
                            ->label(str($resource)->replace('_', ' ')->title())->inline()
                            ->options([
                                0 => 'None',
                                1 => 'Read',
                                // 2 => 'Write',
                                3 => 'Read & Write',
                            ])
                            ->icons([
                                0 => 'tabler-book-off',
                                1 => 'tabler-book',
                                2 => 'tabler-writing',
                                3 => 'tabler-writing',
                            ])
                            ->colors([
                                0 => 'success',
                                1 => 'warning',
                                2 => 'danger',
                                3 => 'danger',
                            ])
                            ->required()
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 1,
                            ])
                            ->default(0),
                        )->all(),
                    ),
                TagsInput::make('allowed_ips')
                    ->placeholder('Example: 127.0.0.1 or 192.168.1.1')
                    ->label('Whitelisted IPv4 Addresses')
                    ->helperText('Press enter to add a new IP address or leave blank to allow any IP address')
                    ->columnSpanFull(),
                Textarea::make('memo')
                    ->required()
                    ->label('Description')
                    ->helperText('
                        Once you have assigned permissions and created this set of credentials you will be unable to come back and edit it.
                        If you need to make changes down the road you will need to create a new set of credentials.
                    ')
                    ->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiKeys::route('/'),
            'create' => Pages\CreateApiKey::route('/create'),
        ];
    }
}
