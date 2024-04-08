<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApiKeyResource\Pages;
use App\Models\ApiKey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;
    protected static ?string $label = 'API Key';

    protected static ?string $navigationIcon = 'tabler-key';

    public static function canEdit($record): bool
    {
        return false;
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Keys'),
            'application' => Tab::make('Application Keys')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('key_type', ApiKey::TYPE_APPLICATION)),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'application';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('identifier')->default(ApiKey::generateTokenIdentifier(ApiKey::TYPE_APPLICATION)),
                Forms\Components\Hidden::make('token')->default(encrypt(str_random(ApiKey::KEY_LENGTH))),

                Forms\Components\Select::make('user_id')
                    ->searchable()
                    ->preload()
                    ->relationship('user', 'username')
                    ->default(auth()->user()->id)
                    ->required(),

                Forms\Components\Select::make('key_type')
                    ->options(function (ApiKey $apiKey) {
                        $originalOptions = [
                            ApiKey::TYPE_NONE => 'None',
                            ApiKey::TYPE_ACCOUNT => 'Account',
                            ApiKey::TYPE_APPLICATION => 'Application',
                            ApiKey::TYPE_DAEMON_USER => 'Daemon User',
                            ApiKey::TYPE_DAEMON_APPLICATION => 'Daemon Application',
                        ];

                        return collect($originalOptions)
                            ->filter(fn ($value, $key) => $key <= ApiKey::TYPE_APPLICATION || $apiKey->key_type === $key)
                            ->all();
                    })
                    ->selectablePlaceholder(false)
                    ->required()
                    ->default(ApiKey::TYPE_APPLICATION),

                Forms\Components\Fieldset::make('Permissions')->schema(
                    collect(ApiKey::RESOURCES)->map(fn ($resource) =>
                        Forms\Components\ToggleButtons::make("r_$resource")
                            ->label(str($resource)->replace('_', ' ')->title())
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
                                0 => 'primary',
                                1 => 'warning',
                                2 => 'danger',
                                3 => 'danger',
                            ])
                            ->inline()
                            ->required()
                            ->disabledOn('edit')
                            ->default(0),
                        )->all(),
                ),

                Forms\Components\TagsInput::make('allowed_ips')
                    ->placeholder('Example: 127.0.0.1 or 192.168.1.1')
                    ->label('Whitelisted IPv4 Addresses')
                    ->helperText('Press enter to add a new IP address or leave blank to allow any IP address')
                    ->columnSpanFull()
                    ->hidden()
                    ->default(null),

                Forms\Components\Textarea::make('memo')
                    ->required()
                    ->label('Description')
                    ->helperText('
                        Once you have assigned permissions and created this set of credentials you will be unable to come back and edit it.
                        If you need to make changes down the road you will need to create a new set of credentials.
                    ')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->searchable()
                    ->hidden()
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->copyable()
                    ->icon('tabler-clipboard-text')
                    ->state(fn (ApiKey $key) => $key->identifier . decrypt($key->token)),

                Tables\Columns\TextColumn::make('memo')
                    ->label('Description')
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('identifier')
                    ->hidden()
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
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
            'index' => Pages\ListApiKeys::route('/'),
            'create' => Pages\CreateApiKey::route('/create'),
        ];
    }
}
