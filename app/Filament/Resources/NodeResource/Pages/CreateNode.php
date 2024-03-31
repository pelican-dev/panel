<?php

namespace App\Filament\Resources\NodeResource\Pages;

use App\Filament\Resources\NodeResource;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\HtmlString;

class CreateNode extends CreateRecord
{
    protected static string $resource = NodeResource::class;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
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

                        if (!is_ip($state) && !empty($state) && !checkdnsrr("$state.", 'A')) {
                            return 'Your hostname does not appear to have a valid A record.';
                        }

                        return '';
                    })
                    ->helperText(fn ($state) => is_ip($state) ? 'You can also enter in the domain name instead!' : 'You can also enter the IP address instead!')
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                        [$subdomain] = str($state)->explode('.', 2);

                        $set('name', $subdomain);
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
                    ->disabled(function (Forms\Get $get, Forms\Set $set) {
                        if (request()->isSecure()) {
                            $set('scheme', 'https');

                            return true;
                        }

                        return false;
                    })
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
                    ->default('http'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->regex('/[a-zA-Z0-9_\.\- ]+/')
                    ->helperText('Character limits: [a-zA-Z0-9_.-] and [Space]')
                    ->maxLength(100),
                Forms\Components\Textarea::make('description')
                    ->hidden()
                    ->columnSpanFull()
                    ->rows(5),
            ]);
    }
}
