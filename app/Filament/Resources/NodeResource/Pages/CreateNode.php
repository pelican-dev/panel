<?php

namespace App\Filament\Resources\NodeResource\Pages;

use App\Filament\Resources\NodeResource;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateNode extends CreateRecord
{
    protected static string $resource = NodeResource::class;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->columns(2)
            ->schema([
                Forms\Components\TextInput::make('fqdn')
                    ->label('Domain Name')
                    ->placeholder('node.example.com')
                    ->helperText('Node\'s Domain Name')
                    ->required()
                    ->autofocus()
                    ->columns(3)
                    ->live(debounce: 500)
                    ->hidden(fn (Forms\Get $get) => !$get('isHostname'))
                    ->disabled(fn (Forms\Get $get) => !$get('isHostname'))
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                        $hasRecords = checkdnsrr("$state.", 'A');
                        if (!$hasRecords) {
                            Notification::make()
                                ->title('Your hostname does not appear to have a valid A record.')
                                ->warning()
                                ->send();
                        }
                    })
                    ->maxLength(191),

                Forms\Components\TextInput::make('fqdn')
                    ->label('IP Address')
                    ->placeholder('127.0.0.1')
                    ->helperText('Node\'s IP Address')
                    ->required()
                    ->ipv4()
                    ->columns(3)
                    ->live(debounce: 500)
                    ->hidden(fn (Forms\Get $get) => $get('isHostname'))
                    ->disabled(fn (Forms\Get $get) => $get('isHostname'))
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                        $isIp = filter_var($state, FILTER_VALIDATE_IP) !== false;
                        $isSecure = request()->isSecure();

                        if ($isIp && $isSecure) {
                            Notification::make()
                                ->title('You cannot use an IP Address because you have a secure connection to the panel currently.')
                                ->danger()
                                ->send();
                            $set('name', $state);
                        }
                    })
                    ->maxLength(191),

                Forms\Components\ToggleButtons::make('isHostname')
                    ->label('Address Type')
                    ->options([
                        true => 'Hostname',
                        false => 'IP Address',
                    ])
                    ->inline()
                    ->live()
                    ->afterStateUpdated(function () {

                    })
                    ->default(true),

                Forms\Components\TextInput::make('daemonListen')
                    ->columns(1)
                    ->label('Port')
                    ->helperText('If you will be running the daemon behind Cloudflare you should set the daemon port to 8443 to allow websocket proxying over SSL.')
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
                    // request()->isSecure()
                    ->helperText(function (Forms\Get $get) {
                        if (request()->isSecure()) {
                            return 'Your Panel is currently using secure connection therefore so must your Daemon.
                                This automatically disables using an IP Address for a FQDN.';
                        }

                        if (filter_var($get('fqdn'), FILTER_VALIDATE_IP) !== false) {
                            return 'An IP address cannot use SSL.';
                        }

                        return '';
                    })
                    // ->helperText(fn (Forms\Get $get) => filter_var($get('fqdn'), FILTER_VALIDATE_IP) !== false ? 'An IP address cannot use SSL.' : '')
                    ->disabled(function (Forms\Get $get, Forms\Set $set) {
                        $isIp = filter_var($get('fqdn'), FILTER_VALIDATE_IP) !== false;
                        $isSecure = request()->isSecure();

                        if ($isSecure) {
                            $set('scheme', 'https');

                            return true;
                        }

                        if ($isIp) {
                            $set('scheme', 'http');

                            return true;
                        }
                    })
                    ->options([
                        'http' => 'HTTP',
                        'https' => 'SSL (HTTPS)',
                    ])
                    ->colors([
                        'http' => 'warning',
                        'https' => 'success',
                    ])
                    ->icons([
                        'http' => 'heroicon-m-lock-open',
                        'https' => 'heroicon-m-lock-closed',
                    ])
                    ->default('http'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->regex('/[a-zA-Z0-9_\.\- ]+/')
                    ->helperText('Character limits: [a-zA-Z0-9_.-] and [Space]')
                    ->maxLength(100),
                Forms\Components\Textarea::make('description')->columnSpanFull()->rows(5),
            ]);
    }
}
