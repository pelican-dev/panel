<?php

namespace App\Filament\Resources\NodeResource\Pages;

use App\Filament\Resources\NodeResource;
use App\Models\Node;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\HtmlString;

class CreateNode extends CreateRecord
{
    protected static string $resource = NodeResource::class;

    private $forceReloadToggle = false;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->debounce(0)
            ->columns(4)
            ->schema([
                Forms\Components\TextInput::make('fqdn')
                    ->columnSpan(2)
                    ->required()
                    ->autofocus()
                    ->live(debounce: 500)
                    ->label(fn ($state) => is_ip($state) ? 'IP Address' : 'Domain Name')
                    ->placeholder(fn ($state) => is_ip($state) ? '192.168.1.1' : 'node.example.com')
                    ->hintColor('danger')
                    ->hint(function ($state) {
                        if (is_ip($state) && request()->isSecure()) {
                            return 'You currently have a secure connection to the panel.';
                        }

//                        if (!is_ip($state) && !empty($state) && !checkdnsrr("$state.", 'A')) {
//                            return 'Your hostname does not appear to have a valid A record.';
//                        }

                        return '';
                    })
                    ->helperText(fn ($state) => is_ip($state) ? 'You can also enter in the domain name instead!' : 'You can also enter the IP address instead!')
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state) {

                        $set('isInsecureIp', is_ip($state) && request()->isSecure());

                        $this->forceReloadToggle = !$this->forceReloadToggle;

                        if (is_ip($state)) {
                            $set('name', '');

                            return;
                        }

                        [$subdomain] = str($state)->explode('.', 2);
                        if (!is_numeric($subdomain)) {
                            $set('name', $subdomain);
                        }
                    })
                    ->maxLength(191),

                Forms\Components\TextInput::make('daemonListen')
                    ->columns(1)
                    ->label('Port')
                    ->helperText('If you are running the daemon behind Cloudflare you should set the daemon port to 8443 to allow websocket proxying over SSL.')
                    ->minValue(0)
                    ->maxValue(65536)
                    ->default(8080)
                    ->required()
                    ->integer(),

                Forms\Components\ToggleButtons::make('scheme')
                    ->label('Communicate over SSL')
                    ->required()
                    ->dehydrated()
                    ->inline()
                    ->helperText(function (Forms\Get $get) {
                        if (request()->isSecure()) {
                            return 'Your Panel is using a secure (SSL/TLS) connection, therefore your Daemon has to as well.';
                        }

                        if (is_ip($get('fqdn'))) {
                            return 'An IP address cannot use SSL.';
                        }

                        return '';
                    })
//                    ->disabled(function (Forms\Get $get, Forms\Set $set) {
//                        if (request()->isSecure()) {
//                            $set('scheme', 'https');
//
//                            return true;
//                        }
//
//                        return false;
//                    })
                    ->options([
                        'http' => 'HTTP',
                        'https' => 'HTTPS (SSL)',
                    ])
                    ->colors([
                        'http' => 'warning',
                        'https' => 'success',
                    ])
                    ->icons([
                        'http' => 'tabler-lock-open-off',
                        'https' => 'tabler-lock',
                    ])
                    ->default(fn () => request()->isSecure() ? 'https' : 'http'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->regex('/[a-zA-Z0-9_\.\- ]+/')
                    ->helperText('This is just a display name and can be changed later. Character limits: a-Z, 0-9, and [.-_ ]')
                    ->maxLength(100),
                Forms\Components\Textarea::make('description')
                    ->hidden()
                    ->columnSpanFull()
                    ->rows(5),
                Forms\Components\Hidden::make('skipValidation')->default(true),
                Forms\Components\Hidden::make('isInsecureIp')->default(false),
            ]);
    }

    protected function getCreateFormAction(): Action
    {
        $label = 'Create';

        if ($this->data['isInsecureIp']) {
            return Action::make('ip-ssl')
                ->label($label)
                ->requiresConfirmation()
                ->modalIconColor('danger')
                ->modalHeading('Using an IP Address with (SSL) Panel')
                ->modalDescription('
                    In order for your Panel to connect to your Node,
                    you must get an SSL certificate for specifically your IP Address.
                    This is very rare and extremely difficult to accomplish.
                    Are you absolutely sure you want to continue?
                ')
                ->modalSubmitAction(false)
                ->extraModalFooterActions([
                    Action::make('create')->action(fn () => $this->create())->color('danger'),
                ]);
        }

        return parent::getCreateFormAction()->label($label);
    }
}
