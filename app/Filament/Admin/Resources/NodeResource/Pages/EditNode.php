<?php

namespace App\Filament\Admin\Resources\NodeResource\Pages;

use App\Filament\Admin\Resources\NodeResource;
use App\Filament\Admin\Resources\NodeResource\Widgets\NodeMemoryChart;
use App\Filament\Admin\Resources\NodeResource\Widgets\NodeStorageChart;
use App\Models\Node;
use App\Services\Nodes\NodeUpdateService;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class EditNode extends EditRecord
{
    protected static string $resource = NodeResource::class;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Tabs::make('Tabs')
                ->columns([
                    'default' => 2,
                    'sm' => 3,
                    'md' => 3,
                    'lg' => 4,
                ])
                ->persistTabInQueryString()
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Basic Settings')
                        ->icon('tabler-server')
                        ->schema([
                            Forms\Components\TextInput::make('fqdn')
                                ->columnSpan(2)
                                ->required()
                                ->autofocus()
                                ->live(debounce: 1500)
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
                                    false => 'Invalid',
                                ])
                                ->colors([
                                    true => 'success',
                                    false => 'danger',
                                ])
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 1,
                                ]),

                            Forms\Components\TextInput::make('daemon_listen')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 1,
                                ])
                                ->label(trans('strings.port'))
                                ->helperText('If you are running the daemon behind Cloudflare you should set the daemon port to 8443 to allow websocket proxying over SSL.')
                                ->minValue(0)
                                ->maxValue(65536)
                                ->default(8080)
                                ->required()
                                ->integer(),

                            Forms\Components\TextInput::make('name')
                                ->label('Display Name')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 2,
                                ])
                                ->required()
                                ->regex('/[a-zA-Z0-9_\.\- ]+/')
                                ->helperText('This name is for display only and can be changed later.')
                                ->maxLength(100),

                            Forms\Components\ToggleButtons::make('scheme')
                                ->label('Communicate over SSL')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 1,
                                ])
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
                                ->default(fn () => request()->isSecure() ? 'https' : 'http'), ]),

                    Tabs\Tab::make('Advanced Settings')
                        ->columns(['default' => 1, 'sm' => 1, 'md' => 4, 'lg' => 6])
                        ->icon('tabler-server-cog')
                        ->schema([
                            Forms\Components\TextInput::make('id')
                                ->label('Node ID')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 1])
                                ->disabled(),
                            Forms\Components\TextInput::make('uuid')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->label('Node UUID')
                                ->hintAction(CopyAction::make())
                                ->disabled(),
                            Forms\Components\TagsInput::make('tags')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->label('Tags')
                                ->disabled()
                                ->placeholder('Not Implemented')
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip('Not Implemented'),
                            Forms\Components\TextInput::make('upload_size')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 1])
                                ->label('Upload Limit')
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip('Enter the maximum size of files that can be uploaded through the web-based file manager.')
                                ->numeric()->required()
                                ->minValue(1)
                                ->maxValue(1024)
                                ->suffix('MiB'),
                            Forms\Components\TextInput::make('daemon_sftp')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 3])
                                ->label('SFTP Port')
                                ->minValue(0)
                                ->maxValue(65536)
                                ->default(2022)
                                ->required()
                                ->integer(),
                            Forms\Components\TextInput::make('daemon_sftp_alias')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 3])
                                ->label('SFTP Alias')
                                ->helperText('Display alias for the SFTP address. Leave empty to use the Node FQDN.'),
                            Forms\Components\ToggleButtons::make('public')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 3])
                                ->label('Automatic Allocation')->inline()
                                ->options([
                                    true => 'Yes',
                                    false => 'No',
                                ])
                                ->colors([
                                    true => 'success',
                                    false => 'danger',
                                ]),
                            Forms\Components\ToggleButtons::make('maintenance_mode')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 3])
                                ->label('Maintenance Mode')->inline()
                                ->hinticon('tabler-question-mark')
                                ->hintIconTooltip("If the node is marked 'Under Maintenance' users won't be able to access servers that are on this node.")
                                ->options([
                                    false => 'Disable',
                                    true => 'Enable',
                                ])
                                ->colors([
                                    false => 'success',
                                    true => 'danger',
                                ]),
                            Forms\Components\Grid::make()
                                ->columns(['default' => 1, 'sm' => 1, 'md' => 3, 'lg' => 6])
                                ->columnSpanFull()
                                ->schema([
                                    Forms\Components\ToggleButtons::make('unlimited_mem')
                                        ->label('Memory')->inlineLabel()->inline()
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('memory', 0))
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('memory_overallocate', 0))
                                        ->formatStateUsing(fn (Forms\Get $get) => $get('memory') == 0)
                                        ->live()
                                        ->options([
                                            true => 'Unlimited',
                                            false => 'Limited',
                                        ])
                                        ->colors([
                                            true => 'primary',
                                            false => 'warning',
                                        ])
                                        ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2]),
                                    Forms\Components\TextInput::make('memory')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_mem'))
                                        ->label('Memory Limit')->inlineLabel()
                                        ->suffix('MiB')
                                        ->required()
                                        ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2])
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('memory_overallocate')
                                        ->dehydratedWhenHidden()
                                        ->label('Overallocate')->inlineLabel()
                                        ->required()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_mem'))
                                        ->hintIcon('tabler-question-mark')
                                        ->hintIconTooltip('The % allowable to go over the set limit.')
                                        ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2])
                                        ->numeric()
                                        ->minValue(-1)
                                        ->maxValue(100)
                                        ->suffix('%'),
                                ]),
                            Forms\Components\Grid::make()
                                ->columns(['default' => 1, 'sm' => 1, 'md' => 3, 'lg' => 6])
                                ->schema([
                                    Forms\Components\ToggleButtons::make('unlimited_disk')
                                        ->label('Disk')->inlineLabel()->inline()
                                        ->live()
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('disk', 0))
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('disk_overallocate', 0))
                                        ->formatStateUsing(fn (Forms\Get $get) => $get('disk') == 0)
                                        ->options([
                                            true => 'Unlimited',
                                            false => 'Limited',
                                        ])
                                        ->colors([
                                            true => 'primary',
                                            false => 'warning',
                                        ])
                                        ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2]),
                                    Forms\Components\TextInput::make('disk')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_disk'))
                                        ->label('Disk Limit')->inlineLabel()
                                        ->suffix('MiB')
                                        ->required()
                                        ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2])
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('disk_overallocate')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_disk'))
                                        ->label('Overallocate')->inlineLabel()
                                        ->hintIcon('tabler-question-mark')
                                        ->hintIconTooltip('The % allowable to go over the set limit.')
                                        ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2])
                                        ->required()
                                        ->numeric()
                                        ->minValue(-1)
                                        ->maxValue(100)
                                        ->suffix('%'),
                                ]),
                            Forms\Components\Grid::make()
                                ->columns(6)
                                ->columnSpanFull()
                                ->schema([
                                    Forms\Components\ToggleButtons::make('unlimited_cpu')
                                        ->label('CPU')->inlineLabel()->inline()
                                        ->live()
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('cpu', 0))
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('cpu_overallocate', 0))
                                        ->formatStateUsing(fn (Forms\Get $get) => $get('cpu') == 0)
                                        ->options([
                                            true => 'Unlimited',
                                            false => 'Limited',
                                        ])
                                        ->colors([
                                            true => 'primary',
                                            false => 'warning',
                                        ])
                                        ->columnSpan(2),
                                    Forms\Components\TextInput::make('cpu')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_cpu'))
                                        ->label('CPU Limit')->inlineLabel()
                                        ->suffix('%')
                                        ->required()
                                        ->columnSpan(2)
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('cpu_overallocate')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_cpu'))
                                        ->label('Overallocate')->inlineLabel()
                                        ->hintIcon('tabler-question-mark')
                                        ->hintIconTooltip('The % allowable to go over the set limit.')
                                        ->columnSpan(2)
                                        ->required()
                                        ->numeric()
                                        ->minValue(-1)
                                        ->maxValue(100)
                                        ->suffix('%'),
                                ]),
                        ]),
                    Tabs\Tab::make('Configuration File')
                        ->icon('tabler-code')
                        ->schema([
                            Forms\Components\Placeholder::make('instructions')
                                ->columnSpanFull()
                                ->content(new HtmlString('
                                  Save this file to your <span title="usually /etc/pelican/">daemon\'s root directory</span>, named <code>config.yml</code>
                            ')),
                            Forms\Components\Textarea::make('config')
                                ->label('/etc/pelican/config.yml')
                                ->disabled()
                                ->rows(19)
                                ->hintAction(CopyAction::make())
                                ->columnSpanFull(),
                            Forms\Components\Actions::make([
                                Forms\Components\Actions\Action::make('resetKey')
                                    ->label('Reset Daemon Token')
                                    ->color('danger')
                                    ->requiresConfirmation()
                                    ->modalHeading('Reset Daemon Token?')
                                    ->modalDescription('Resetting the daemon token will void any request coming from the old token. This token is used for all sensitive operations on the daemon including server creation and deletion. We suggest changing this token regularly for security.')
                                    ->action(fn (NodeUpdateService $nodeUpdateService, Node $node) => $nodeUpdateService->handle($node, [], true)
                                        && Notification::make()->success()->title('Daemon Key Reset')->send()
                                        && $this->fillForm()
                                    ),
                            ]),
                        ]),
                ]),
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $node = Node::findOrFail($data['id']);

        $data['config'] = $node->getYamlConfiguration();

        return $data;
    }

    protected function getFormActions(): array
    {
        return [];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (Node $node) => $node->servers()->count() > 0)
                ->label(fn (Node $node) => $node->servers()->count() > 0 ? 'Node Has Servers' : 'Delete'),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            NodeStorageChart::class,
            NodeMemoryChart::class,
        ];
    }

    protected function afterSave(): void
    {
        $this->fillForm();
    }
}
