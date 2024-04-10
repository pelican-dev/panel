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

    protected static bool $canCreateAnother = false;

    protected ?string $subheading = '(a machine that runs Wings to connect back to this Panel)';

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
                    ->rule('prohibited', fn ($state) => is_ip($state) && request()->isSecure())
                    ->label(fn ($state) => is_ip($state) ? 'IP Address' : 'Domain Name')
                    ->placeholder(fn ($state) => is_ip($state) ? '192.168.1.1' : 'node.example.com')
                    ->helperText(function ($state) {
                        if (is_ip($state)) {
                            if (request()->isSecure()) {
                                return '
                                    Your panel is currently secured via an SSL certificate and that means your nodes require one too.
                                    You must use a domain name, because you cannot get SSL certificates for IP Addresses
                                ';
                            }

                            return '';
                        }

                        return "
                            This is the domain name that points to your node's IP Address.
                            If you've already set up this, you can verify it by checking the next field!
                        ";
                    })
                    ->hintColor('danger')
                    ->hint(function ($state) {
                        if (is_ip($state) && request()->isSecure()) {
                            return 'You cannot connect to an IP Address over SSL';
                        }

                        return '';
                    })
                    ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                        $set('dns', null);
                        $set('ip', null);

                        [$subdomain] = str($state)->explode('.', 2);
                        if (!is_numeric($subdomain)) {
                            $set('name', $subdomain);
                        }

                        if (!$state || is_ip($state)) {
                            $set('dns', null);

                            return;
                        }

                        $validRecords = gethostbynamel($state);
                        if ($validRecords) {
                            $set('dns', true);

                            $set('ip', collect($validRecords)->first());

                            return;
                        }

                        $set('dns', false);
                    })
                    ->maxLength(191),

                Forms\Components\TextInput::make('ip')
                    ->disabled()
                    ->hidden(),

                Forms\Components\ToggleButtons::make('dns')
                    ->label('DNS Record Check')
                    ->helperText('This lets you know if your DNS record correctly points to an IP Address.')
                    ->disabled()
                    ->inline()
                    ->default(null)
                    ->hint(fn (Forms\Get $get) => $get('ip'))
                    ->hintColor('success')
                    ->options([
                        true => 'Valid',
                        false => 'Invalid'
                    ])
                    ->colors([
                        true => 'success',
                        false => 'danger'
                    ]),

                Forms\Components\TextInput::make('daemonListen')
                    ->columnSpan(1)
                    ->label('Port')
                    ->helperText('If you are running the daemon behind Cloudflare you should set the daemon port to 8443 to allow websocket proxying over SSL.')
                    ->minValue(0)
                    ->maxValue(65536)
                    ->default(8080)
                    ->required()
                    ->integer(),



                Forms\Components\TextInput::make('name')
                    ->label('Display Name')
                    ->columnSpan(2)
                    ->required()
                    ->regex('/[a-zA-Z0-9_\.\- ]+/')
                    ->helperText('This name is for display only and can be changed later.')
                    ->maxLength(100),

                Forms\Components\ToggleButtons::make('scheme')
                    ->label('Communicate over SSL')
                    ->columnSpan(2)
                    ->required()
                    ->inline()
                    ->helperText(function (Forms\Get $get) {
                        if (request()->isSecure()) {
                            return new HtmlString('Your Panel is using a secure SSL connection,<br>so your Daemon must too.');
                        }

                        if (is_ip($get('fqdn'))) {
                            return 'An IP address cannot use SSL.';
                        }

                        return '';
                    })
                    ->disableOptionWhen(fn (string $value): bool => $value === 'http' && request()->isSecure())
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

                Forms\Components\Textarea::make('description')
                    ->hidden()
                    ->columnSpanFull()
                    ->rows(5),

                Forms\Components\Hidden::make('skipValidation')->default(true),
            ]);
    }

    protected function getRedirectUrlParameters(): array
    {
        return [
            'tab' => '-configuration-tab',
        ];
    }
}
