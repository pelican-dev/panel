<?php

namespace App\Filament\Admin\Resources\ApiKeyResource\Pages;

use App\Filament\Admin\Resources\ApiKeyResource;
use App\Models\ApiKey;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;

class CreateApiKey extends CreateRecord
{
    protected static string $resource = ApiKeyResource::class;

    protected ?string $heading = 'Create Application API Key';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('identifier')->default(ApiKey::generateTokenIdentifier(ApiKey::TYPE_APPLICATION)),
                Forms\Components\Hidden::make('token')->default(encrypt(str_random(ApiKey::KEY_LENGTH))),

                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->user()->id)
                    ->required(),

                Forms\Components\Select::make('key_type')
                    ->inlineLabel()
                    ->options(function (ApiKey $apiKey) {
                        $originalOptions = [
                                //ApiKey::TYPE_NONE => 'None',
                            ApiKey::TYPE_ACCOUNT => 'Account',
                            ApiKey::TYPE_APPLICATION => 'Application',
                            //ApiKey::TYPE_DAEMON_USER => 'Daemon User',
                            //ApiKey::TYPE_DAEMON_APPLICATION => 'Daemon Application',
                        ];

                        return collect($originalOptions)
                            ->filter(fn($value, $key) => $key <= ApiKey::TYPE_APPLICATION || $apiKey->key_type === $key)
                            ->all();
                    })
                    ->selectablePlaceholder(false)
                    ->required()
                    ->default(ApiKey::TYPE_APPLICATION),

                Forms\Components\Fieldset::make('Permissions')
                    ->columns([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->schema(
                        collect(ApiKey::RESOURCES)->map(
                            fn($resource) => Forms\Components\ToggleButtons::make("r_$resource")
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
}
