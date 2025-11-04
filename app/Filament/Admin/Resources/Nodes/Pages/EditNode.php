<?php

namespace App\Filament\Admin\Resources\Nodes\Pages;

use App\Filament\Admin\Resources\Nodes\NodeResource;
use App\Models\Node;
use App\Repositories\Daemon\DaemonConfigurationRepository;
use App\Services\Helpers\SoftwareVersionService;
use App\Services\Nodes\NodeAutoDeployService;
use App\Services\Nodes\NodeUpdateService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\CodeEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconSize;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\HtmlString;
use Phiki\Grammar\Grammar;
use Throwable;

class EditNode extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = NodeResource::class;

    private DaemonConfigurationRepository $daemonConfigurationRepository;

    private NodeUpdateService $nodeUpdateService;

    public function boot(DaemonConfigurationRepository $daemonConfigurationRepository, NodeUpdateService $nodeUpdateService): void
    {
        $this->daemonConfigurationRepository = $daemonConfigurationRepository;
        $this->nodeUpdateService = $nodeUpdateService;
    }

    /**
     * @throws Throwable
     */
    public function form(Schema $schema): Schema
    {
        return $schema->components([
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
                    Tab::make('overview')
                        ->label(trans('admin/node.tabs.overview'))
                        ->icon('tabler-chart-area-line-filled')
                        ->columns([
                            'default' => 4,
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ])
                        ->schema([
                            Fieldset::make()
                                ->label(trans('admin/node.node_info'))
                                ->columns(4)
                                ->columnSpanFull()
                                ->schema([
                                    TextEntry::make('wings_version')
                                        ->label(trans('admin/node.wings_version'))
                                        ->state(fn (Node $node, SoftwareVersionService $versionService) => ($node->systemInformation()['version'] ?? trans('admin/node.unknown')) . ' ' . trans('admin/node.latest', ['version' => $versionService->latestWingsVersion()])),
                                    TextEntry::make('cpu_threads')
                                        ->label(trans('admin/node.cpu_threads'))
                                        ->state(fn (Node $node) => $node->systemInformation()['cpu_count'] ?? 0),
                                    TextEntry::make('architecture')
                                        ->label(trans('admin/node.architecture'))
                                        ->state(fn (Node $node) => $node->systemInformation()['architecture'] ?? trans('admin/node.unknown')),
                                    TextEntry::make('kernel')
                                        ->label(trans('admin/node.kernel'))
                                        ->state(fn (Node $node) => $node->systemInformation()['kernel_version'] ?? trans('admin/node.unknown')),
                                ]),
                            View::make('filament.components.node-cpu-chart')
                                ->columnSpan([
                                    'default' => 4,
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ]),
                            View::make('filament.components.node-memory-chart')
                                ->columnSpan([
                                    'default' => 4,
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ]),
                            View::make('filament.components.node-storage-chart')
                                ->columnSpanFull(),
                        ]),
                    Tab::make('basic_settings')
                        ->label(trans('admin/node.tabs.basic_settings'))
                        ->icon('tabler-server')
                        ->schema([
                            TextInput::make('fqdn')
                                ->columnSpan(2)
                                ->required()
                                ->autofocus()
                                ->live(debounce: 1500)
                                ->rules(Node::getRulesForField('fqdn'))
                                ->prohibited(fn ($state) => is_ip($state) && request()->isSecure())
                                ->label(fn ($state) => is_ip($state) ? trans('admin/node.ip_address') : trans('admin/node.domain'))
                                ->placeholder(fn ($state) => is_ip($state) ? '192.168.1.1' : 'node.example.com')
                                ->helperText(function ($state) {
                                    if (is_ip($state)) {
                                        if (request()->isSecure()) {
                                            return trans('admin/node.fqdn_help');
                                        }

                                        return '';
                                    }

                                    return trans('admin/node.error');
                                })
                                ->hintColor('danger')
                                ->hint(function ($state) {
                                    if (is_ip($state) && request()->isSecure()) {
                                        return trans('admin/node.ssl_ip');
                                    }

                                    return '';
                                })
                                ->afterStateUpdated(function (Set $set, ?string $state) {
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

                                    $ip = get_ip_from_hostname($state);
                                    if ($ip) {
                                        $set('dns', true);

                                        $set('ip', $ip);
                                    } else {
                                        $set('dns', false);
                                    }
                                })
                                ->maxLength(255),
                            TextInput::make('ip')
                                ->disabled()
                                ->hidden(),
                            ToggleButtons::make('dns')
                                ->label(trans('admin/node.dns'))
                                ->helperText(trans('admin/node.dns_help'))
                                ->disabled()
                                ->inline()
                                ->default(null)
                                ->hint(fn (Get $get) => $get('ip'))
                                ->hintColor('success')
                                ->stateCast(new BooleanStateCast(false, true))
                                ->options([
                                    1 => trans('admin/node.valid'),
                                    0 => trans('admin/node.invalid'),
                                ])
                                ->colors([
                                    1 => 'success',
                                    0 => 'danger',
                                ])
                                ->columnSpan(1),
                            TextInput::make('daemon_connect')
                                ->columnSpan(1)
                                ->label(fn (Get $get) => $get('connection') === 'https_proxy' ? trans('admin/node.connect_port') : trans('admin/node.port'))
                                ->helperText(fn (Get $get) => $get('connection') === 'https_proxy' ? trans('admin/node.connect_port_help') : trans('admin/node.port_help'))
                                ->minValue(1)
                                ->maxValue(65535)
                                ->default(8080)
                                ->required()
                                ->integer(),
                            TextInput::make('name')
                                ->label(trans('admin/node.display_name'))
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 2,
                                ])
                                ->required()
                                ->maxLength(100),
                            Hidden::make('scheme'),
                            Hidden::make('behind_proxy'),
                            ToggleButtons::make('connection')
                                ->label(trans('admin/node.ssl'))
                                ->columnSpan(1)
                                ->inline()
                                ->helperText(function (Get $get) {
                                    if (request()->isSecure()) {
                                        return new HtmlString(trans('admin/node.panel_on_ssl'));
                                    }

                                    if (is_ip($get('fqdn'))) {
                                        return trans('admin/node.ssl_help');
                                    }

                                    return '';
                                })
                                ->disableOptionWhen(fn (string $value) => $value === 'http' && request()->isSecure())
                                ->options([
                                    'http' => 'HTTP',
                                    'https' => 'HTTPS (SSL)',
                                    'https_proxy' => 'HTTPS with (reverse) proxy',
                                ])
                                ->colors([
                                    'http' => 'warning',
                                    'https' => 'success',
                                    'https_proxy' => 'success',
                                ])
                                ->icons([
                                    'http' => 'tabler-lock-open-off',
                                    'https' => 'tabler-lock',
                                    'https_proxy' => 'tabler-shield-lock',
                                ])
                                ->formatStateUsing(fn (Get $get) => $get('scheme') === 'http' ? 'http' : ($get('behind_proxy') ? 'https_proxy' : 'https'))
                                ->live()
                                ->dehydrated(false)
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $set('scheme', $state === 'http' ? 'http' : 'https');
                                    $set('behind_proxy', $state === 'https_proxy');

                                    $set('daemon_connect', $state === 'https_proxy' ? 443 : 8080);
                                    $set('daemon_listen', 8080);
                                }),
                            TextInput::make('daemon_listen')
                                ->columnSpan(1)
                                ->label(trans('admin/node.listen_port'))
                                ->helperText(trans('admin/node.listen_port_help'))
                                ->minValue(1)
                                ->maxValue(65535)
                                ->default(8080)
                                ->required()
                                ->integer()
                                ->visible(fn (Get $get) => $get('connection') === 'https_proxy'),
                        ]),
                    Tab::make('advanced_settings')
                        ->label(trans('admin/node.tabs.advanced_settings'))
                        ->columns([
                            'default' => 1,
                            'sm' => 1,
                            'md' => 4,
                            'lg' => 6,
                        ])
                        ->icon('tabler-server-cog')
                        ->schema([
                            TextInput::make('id')
                                ->label(trans('admin/node.node_id'))
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 1,
                                ])
                                ->disabled(),
                            TextInput::make('uuid')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ])
                                ->label(trans('admin/node.node_uuid'))
                                ->hintCopy()
                                ->disabled(),
                            TagsInput::make('tags')
                                ->label(trans('admin/node.tags'))
                                ->placeholder('')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 2,
                                ]),
                            TextInput::make('upload_size')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 2,
                                    'lg' => 1,
                                ])
                                ->label(trans('admin/node.upload_limit'))
                                ->hintIcon('tabler-question-mark', trans('admin/node.upload_limit_help.0') . trans('admin/node.upload_limit_help.1'))
                                ->numeric()->required()
                                ->minValue(1)
                                ->maxValue(1024)
                                ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB'),
                            TextInput::make('daemon_sftp')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 3,
                                ])
                                ->label(trans('admin/node.sftp_port'))
                                ->minValue(1)
                                ->maxValue(65535)
                                ->default(2022)
                                ->required()
                                ->integer(),
                            TextInput::make('daemon_sftp_alias')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 3,
                                ])
                                ->label(trans('admin/node.sftp_alias'))
                                ->helperText(trans('admin/node.sftp_alias_help')),
                            ToggleButtons::make('public')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 3,
                                ])
                                ->label(trans('admin/node.use_for_deploy'))
                                ->inline()
                                ->stateCast(new BooleanStateCast(false, true))
                                ->options([
                                    1 => trans('admin/node.yes'),
                                    0 => trans('admin/node.no'),
                                ])
                                ->colors([
                                    1 => 'success',
                                    0 => 'danger',
                                ]),
                            ToggleButtons::make('maintenance_mode')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 3,
                                ])
                                ->label(trans('admin/node.maintenance_mode'))
                                ->inline()
                                ->hintIcon('tabler-question-mark', trans('admin/node.maintenance_mode_help'))
                                ->stateCast(new BooleanStateCast(false, true))
                                ->options([
                                    1 => trans('admin/node.enabled'),
                                    0 => trans('admin/node.disabled'),
                                ])
                                ->colors([
                                    1 => 'danger',
                                    0 => 'success',
                                ]),
                            Grid::make()
                                ->columns([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 3,
                                    'lg' => 6,
                                ])
                                ->columnSpanFull()
                                ->schema([
                                    ToggleButtons::make('unlimited_mem')
                                        ->dehydrated()
                                        ->label(trans('admin/node.memory'))->inlineLabel()->inline()
                                        ->afterStateUpdated(fn (Set $set) => $set('memory', 0))
                                        ->afterStateUpdated(fn (Set $set) => $set('memory_overallocate', 0))
                                        ->formatStateUsing(fn (Get $get) => $get('memory') == 0)
                                        ->live()
                                        ->stateCast(new BooleanStateCast(false, true))
                                        ->options([
                                            1 => trans('admin/node.unlimited'),
                                            0 => trans('admin/node.limited'),
                                        ])
                                        ->colors([
                                            1 => 'primary',
                                            0 => 'warning',
                                        ])
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 1,
                                            'lg' => 2,
                                        ]),
                                    TextInput::make('memory')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Get $get) => $get('unlimited_mem'))
                                        ->label(trans('admin/node.memory_limit'))->inlineLabel()
                                        ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                        ->required()
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 1,
                                            'lg' => 2,
                                        ])
                                        ->numeric()
                                        ->minValue(0),
                                    TextInput::make('memory_overallocate')
                                        ->dehydratedWhenHidden()
                                        ->label(trans('admin/node.overallocate'))->inlineLabel()
                                        ->required()
                                        ->hidden(fn (Get $get) => $get('unlimited_mem'))
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 1,
                                            'lg' => 2,
                                        ])
                                        ->numeric()
                                        ->minValue(-1)
                                        ->maxValue(100)
                                        ->suffix('%'),
                                ]),
                            Grid::make()
                                ->columnSpanFull()
                                ->columns([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 3,
                                    'lg' => 6,
                                ])
                                ->schema([
                                    ToggleButtons::make('unlimited_disk')
                                        ->dehydrated()
                                        ->label(trans('admin/node.disk'))->inlineLabel()->inline()
                                        ->live()
                                        ->afterStateUpdated(fn (Set $set) => $set('disk', 0))
                                        ->afterStateUpdated(fn (Set $set) => $set('disk_overallocate', 0))
                                        ->formatStateUsing(fn (Get $get) => $get('disk') == 0)
                                        ->stateCast(new BooleanStateCast(false, true))
                                        ->options([
                                            1 => trans('admin/node.unlimited'),
                                            0 => trans('admin/node.limited'),
                                        ])
                                        ->colors([
                                            1 => 'primary',
                                            0 => 'warning',
                                        ])
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 1,
                                            'lg' => 2,
                                        ]),
                                    TextInput::make('disk')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Get $get) => $get('unlimited_disk'))
                                        ->label(trans('admin/node.disk_limit'))->inlineLabel()
                                        ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                        ->required()
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 1,
                                            'lg' => 2,
                                        ])
                                        ->numeric()
                                        ->minValue(0),
                                    TextInput::make('disk_overallocate')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Get $get) => $get('unlimited_disk'))
                                        ->label(trans('admin/node.overallocate'))->inlineLabel()
                                        ->columnSpan([
                                            'default' => 1,
                                            'sm' => 1,
                                            'md' => 1,
                                            'lg' => 2,
                                        ])
                                        ->required()
                                        ->numeric()
                                        ->minValue(-1)
                                        ->maxValue(100)
                                        ->suffix('%'),
                                ]),
                            Grid::make()
                                ->columns(6)
                                ->columnSpanFull()
                                ->schema([
                                    ToggleButtons::make('unlimited_cpu')
                                        ->dehydrated()
                                        ->label(trans('admin/node.cpu'))->inlineLabel()->inline()
                                        ->live()
                                        ->afterStateUpdated(fn (Set $set) => $set('cpu', 0))
                                        ->afterStateUpdated(fn (Set $set) => $set('cpu_overallocate', 0))
                                        ->formatStateUsing(fn (Get $get) => $get('cpu') == 0)
                                        ->stateCast(new BooleanStateCast(false, true))
                                        ->options([
                                            1 => trans('admin/node.unlimited'),
                                            0 => trans('admin/node.limited'),
                                        ])
                                        ->colors([
                                            1 => 'primary',
                                            0 => 'warning',
                                        ])
                                        ->columnSpan(2),
                                    TextInput::make('cpu')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Get $get) => $get('unlimited_cpu'))
                                        ->label(trans('admin/node.cpu_limit'))->inlineLabel()
                                        ->suffix('%')
                                        ->required()
                                        ->columnSpan(2)
                                        ->numeric()
                                        ->minValue(0),
                                    TextInput::make('cpu_overallocate')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Get $get) => $get('unlimited_cpu'))
                                        ->label(trans('admin/node.overallocate'))->inlineLabel()
                                        ->columnSpan(2)
                                        ->required()
                                        ->numeric()
                                        ->minValue(-1)
                                        ->maxValue(100)
                                        ->suffix('%'),
                                ]),
                        ]),
                    Tab::make('config_file')
                        ->label(trans('admin/node.tabs.config_file'))
                        ->icon('tabler-code')
                        ->schema([
                            TextEntry::make('instructions')
                                ->label(trans('admin/node.instructions'))
                                ->columnSpanFull()
                                ->state(new HtmlString(trans('admin/node.instructions_help'))),
                            CodeEntry::make('config')
                                ->label('/etc/pelican/config.yml')
                                ->grammar(Grammar::Yaml)
                                ->state(fn (Node $node) => $node->getYamlConfiguration())
                                ->copyable()
                                ->disabled()
                                ->columnSpanFull(),
                            Grid::make()
                                ->columns()
                                ->columnSpanFull()
                                ->schema([
                                    Actions::make([
                                        Action::make('autoDeploy')
                                            ->label(trans('admin/node.auto_deploy'))
                                            ->color('primary')
                                            ->modalHeading(trans('admin/node.auto_deploy'))
                                            ->icon('tabler-rocket')
                                            ->modalSubmitAction(false)
                                            ->modalCancelAction(false)
                                            ->modalFooterActionsAlignment(Alignment::Center)
                                            ->schema([
                                                ToggleButtons::make('docker')
                                                    ->label(trans('admin/node.auto_label'))
                                                    ->live()
                                                    ->helperText(trans('admin/node.auto_question'))
                                                    ->inline()
                                                    ->default(false)
                                                    ->afterStateUpdated(fn (bool $state, NodeAutoDeployService $service, Node $node, Set $set) => $set('generatedToken', $service->handle(request(), $node, $state)))
                                                    ->stateCast(new BooleanStateCast(false, true))
                                                    ->options([
                                                        0 => trans('admin/node.standalone'),
                                                        1 => trans('admin/node.docker'),
                                                    ])
                                                    ->colors([
                                                        0 => 'primary',
                                                        1 => 'success',
                                                    ])
                                                    ->columnSpan(1),
                                                Textarea::make('generatedToken')
                                                    ->label(trans('admin/node.auto_command'))
                                                    ->readOnly()
                                                    ->autosize()
                                                    ->hintCopy()
                                                    ->formatStateUsing(fn (NodeAutoDeployService $service, Node $node, Set $set, Get $get) => $set('generatedToken', $service->handle(request(), $node, $get('docker')))),
                                            ])
                                            ->mountUsing(function (Schema $schema) {
                                                $schema->fill();
                                            }),
                                    ])->fullWidth(),
                                    Actions::make([
                                        Action::make('resetKey')
                                            ->label(trans('admin/node.reset_token'))
                                            ->color('danger')
                                            ->requiresConfirmation()
                                            ->modalHeading(trans('admin/node.reset_token'))
                                            ->modalDescription(trans('admin/node.reset_help'))
                                            ->action(function (Node $node) {
                                                try {
                                                    $this->nodeUpdateService->handle($node, [], true);
                                                } catch (Exception) {
                                                    Notification::make()
                                                        ->title(trans('admin/node.error_connecting', ['node' => $node->name]))
                                                        ->body(trans('admin/node.error_connecting_description'))
                                                        ->color('warning')
                                                        ->icon('tabler-database')
                                                        ->warning()
                                                        ->send();

                                                }
                                                Notification::make()->success()->title(trans('admin/node.token_reset'))->send();
                                                $this->fillForm();
                                            }),
                                    ])->fullWidth(),
                                ]),
                        ]),
                    Tab::make('diagnostics')
                        ->label(trans('admin/node.tabs.diagnostics'))
                        ->icon('tabler-heart-search')
                        ->schema([
                            Section::make('diag')
                                ->heading(trans('admin/node.tabs.diagnostics'))
                                ->columnSpanFull()
                                ->columns(3)
                                ->collapsible()->collapsed()
                                ->headerActions([
                                    Action::make('pull')
                                        ->label(trans('admin/node.diagnostics.pull'))
                                        ->icon('tabler-cloud-download')->iconButton()->iconSize(IconSize::ExtraLarge)
                                        ->visible(fn (Get $get) => !($get('pulled') ?? false))
                                        ->action(function (Get $get, Set $set, Node $node) {
                                            $includeEndpoints = $get('include_endpoints') ?? false;
                                            $includeLogs = $get('include_logs') ?? false;
                                            $logLines = $get('log_lines') ?? 200;
                                            $output = '
Pelican Wings - Diagnostics Report

|
| Versions
| ------------------------------
               Wings: 1.0.0-beta18
              Docker: 28.5.1
              Kernel: 6.8.0-86-generic
                  OS: Ubuntu 24.04.3 LTS

|
| Wings Configuration
| ------------------------------
      Panel Location: {redacted}

  Internal Webserver: {redacted} : 8080
         SSL Enabled: false
     SSL Certificate: {redacted}
             SSL Key: {redacted}

         SFTP Server: {redacted} : 2022
      SFTP Read-Only: false

      Root Directory: /var/lib/pelican
      Logs Directory: /var/log/pelican
      Data Directory: /var/lib/pelican/volumes
   Archive Directory: /var/lib/pelican/archives
    Backup Directory: /var/lib/pelican/backups

            Username: pelican
         Server Time: Mon, 03 Nov 2025 20:32:34 +0100
          Debug Mode: false

|
| Docker: Info
| ------------------------------
Server Version: 28.5.1
Storage Driver: overlay2
  Backing Filesystem: extfs
  Supports d_type: true
  Using metacopy: false
  Native Overlay Diff: true
  userxattr: false
LoggingDriver: json-file
 CgroupDriver: systemd

|
| Docker: Running Containers
| ------------------------------
CONTAINER ID   IMAGE                             COMMAND                  CREATED       STATUS       PORTS                                                                                                                NAMES
00e2a3328817   ghcr.io/parkervcp/yolks:java_21   "/usr/bin/tini -g --â€¦"   9 hours ago   Up 9 hours   192.168.0.6:25570-25571->25570-25571/tcp, 192.168.0.6:25570-25571->25570-25571/udp                                   d4da20d8-7852-4d3e-adbd-23dabfd365bc
a2c0edc05373   ghcr.io/parkervcp/yolks:java_21   "/usr/bin/tini -g --â€¦"   9 hours ago   Up 9 hours   192.168.0.6:8163->8163/tcp, 192.168.0.6:8163->8163/udp, 192.168.0.6:25566->25566/tcp, 192.168.0.6:25566->25566/udp   2e07026f-ef54-4125-ad02-1d4bdf673bd5

|
| Latest Wings Logs
| ------------------------------
 INFO: [Oct 29 16:09:08.483] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Oct 29 16:09:08.697] releasing exclusive lock for power action action=restart lock_id=36a890b2-b4d9-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 29 16:11:51.685] acquired exclusive lock on power actions, processing event... action=restart lock_id=99085fa9-b4d9-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 29 16:11:52.699] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 29 16:11:52.721] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 29 16:11:52.727] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 29 16:11:52.863] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 29 16:11:53.486] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Oct 29 16:11:53.705] releasing exclusive lock for power action action=restart lock_id=99085fa9-b4d9-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:33:09.737] acquired exclusive lock on power actions, processing event... action=stop lock_id=f9b09611-b594-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:33:11.068] releasing exclusive lock for power action action=stop lock_id=f9b09611-b594-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:33:16.405] acquired exclusive lock on power actions, processing event... action=start lock_id=fdaa0eec-b594-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:33:16.405] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:33:16.423] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:33:16.431] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:33:16.569] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:33:17.236] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Oct 30 14:33:17.468] releasing exclusive lock for power action action=start lock_id=fdaa0eec-b594-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:35:10.941] detected server as entering a crashed state; running crash handler server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:35:11.215] acquired exclusive lock on power actions, processing event... action=start lock_id=4218ab26-b595-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:35:11.215] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:35:11.242] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:35:11.247] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:35:11.394] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:35:12.011] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Oct 30 14:35:12.213] releasing exclusive lock for power action action=start lock_id=4218ab26-b595-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:38:36.972] detected server as entering a crashed state; running crash handler server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:38:37.241] acquired exclusive lock on power actions, processing event... action=start lock_id=bce5c3c7-b595-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:38:37.241] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:38:37.268] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:38:37.274] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:38:37.408] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 14:38:38.013] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Oct 30 14:38:38.233] releasing exclusive lock for power action action=start lock_id=bce5c3c7-b595-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:35:32.781] acquired exclusive lock on power actions, processing event... action=stop lock_id=b0b7d8cb-b59d-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:35:33.755] releasing exclusive lock for power action action=stop lock_id=b0b7d8cb-b59d-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:35:51.132] acquired exclusive lock on power actions, processing event... action=start lock_id=bba7e70d-b59d-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:35:51.132] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:35:51.151] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:35:51.158] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:35:51.293] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:35:52.539] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Oct 30 15:35:52.750] releasing exclusive lock for power action action=start lock_id=bba7e70d-b59d-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:38:44.258] acquired exclusive lock on power actions, processing event... action=restart lock_id=22d8d9a7-b59e-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:38:45.155] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:38:45.180] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:38:45.184] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:38:45.321] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 15:38:45.986] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Oct 30 15:38:46.180] releasing exclusive lock for power action action=restart lock_id=22d8d9a7-b59e-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Oct 30 20:45:19.629] acquired exclusive lock on power actions, processing event... action=start lock_id=f757cb64-b5c8-11f0-b114-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Oct 30 20:45:19.629] syncing server configuration with panel server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Oct 30 20:45:19.646] performing server limit modification on-the-fly server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Oct 30 20:45:19.651] performing server limit modification on-the-fly server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Oct 30 20:45:19.783] completed server preflight, starting boot process... server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Oct 30 20:45:20.409] starting resource polling for container container_id=a67db310-6b08-4bd6-95d0-8fa0ac094a94 environment=docker
 INFO: [Oct 30 20:45:20.581] releasing exclusive lock for power action action=start lock_id=f757cb64-b5c8-11f0-b114-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  2 14:04:23.873] acquired exclusive lock on power actions, processing event... action=restart lock_id=743befe7-b7ec-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:24.916] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:24.946] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:24.950] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:25.080] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:25.696] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Nov  2 14:04:25.898] releasing exclusive lock for power action action=restart lock_id=743befe7-b7ec-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:54.880] acquired exclusive lock on power actions, processing event... action=restart lock_id=86b72b02-b7ec-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:55.846] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:55.870] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:55.875] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:56.021] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:04:56.624] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Nov  2 14:04:56.866] releasing exclusive lock for power action action=restart lock_id=86b72b02-b7ec-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:06:00.814] acquired exclusive lock on power actions, processing event... action=restart lock_id=ae03f729-b7ec-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:06:01.786] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:06:01.811] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:06:01.816] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:06:01.949] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:06:02.559] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Nov  2 14:06:02.782] releasing exclusive lock for power action action=restart lock_id=ae03f729-b7ec-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:09:09.181] acquired exclusive lock on power actions, processing event... action=restart lock_id=1e4a85db-b7ed-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:09:10.844] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:09:10.870] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:09:10.875] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:09:11.019] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  2 14:09:11.612] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Nov  2 14:09:11.845] releasing exclusive lock for power action action=restart lock_id=1e4a85db-b7ed-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:29:55.678] acquired exclusive lock on power actions, processing event... action=stop lock_id=0a5f4aeb-b8a0-11f0-b114-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:29:57.339] releasing exclusive lock for power action action=stop lock_id=0a5f4aeb-b8a0-11f0-b114-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:30:03.828] acquired exclusive lock on power actions, processing event... action=stop lock_id=0f3ade97-b8a0-11f0-b114-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:30:04.976] releasing exclusive lock for power action action=stop lock_id=0f3ade97-b8a0-11f0-b114-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:30:07.759] acquired exclusive lock on power actions, processing event... action=stop lock_id=1192baef-b8a0-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:30:08.693] releasing exclusive lock for power action action=stop lock_id=1192baef-b8a0-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:32:14.511] acquired exclusive lock on power actions, processing event... action=start lock_id=5d1f839a-b8a0-11f0-b114-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:32:14.511] syncing server configuration with panel server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:32:14.532] performing server limit modification on-the-fly server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:32:14.539] performing server limit modification on-the-fly server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:32:14.558] completed server preflight, starting boot process... server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:32:23.070] starting resource polling for container container_id=d4da20d8-7852-4d3e-adbd-23dabfd365bc environment=docker
 INFO: [Nov  3 11:32:23.288] releasing exclusive lock for power action action=start lock_id=5d1f839a-b8a0-11f0-b114-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:32:29.928] acquired exclusive lock on power actions, processing event... action=start lock_id=664ff8c0-b8a0-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:32:29.928] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:32:29.956] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:32:29.963] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:32:30.172] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:32:30.781] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Nov  3 11:32:30.941] releasing exclusive lock for power action action=start lock_id=664ff8c0-b8a0-11f0-b114-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:32:37.027] acquired exclusive lock on power actions, processing event... action=start lock_id=6a8b4f4c-b8a0-11f0-b114-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:32:37.027] syncing server configuration with panel server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:32:37.078] performing server limit modification on-the-fly server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:32:37.086] performing server limit modification on-the-fly server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:32:37.354] completed server preflight, starting boot process... server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:32:38.008] starting resource polling for container container_id=a67db310-6b08-4bd6-95d0-8fa0ac094a94 environment=docker
 INFO: [Nov  3 11:32:38.244] releasing exclusive lock for power action action=start lock_id=6a8b4f4c-b8a0-11f0-b114-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:33:17.561] writing log files to disk path=/var/log/pelican/wings.log
 INFO: [Nov  3 11:33:17.561] loading configuration from file config_file=/etc/pelican/config.yml
 INFO: [Nov  3 11:33:17.569] configured wings with system timezone timezone=Etc/UTC
 INFO: [Nov  3 11:33:17.569] checking for pelican system user username=pelican
 INFO: [Nov  3 11:33:17.569] configured system user successfully gid=987 uid=999 username=pelican
 INFO: [Nov  3 11:33:17.573] fetching list of servers from API
 INFO: [Nov  3 11:33:17.920] processing servers returned by the API total_configs=3
 INFO: [Nov  3 11:33:17.921] creating new server object from API response server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:17.921] creating new server object from API response server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:17.921] creating new server object from API response server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:33:17.922] finished processing server configurations duration=1.437131ms
 INFO: [Nov  3 11:33:17.923] finished loading configuration for server server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:17.924] finished loading configuration for server server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:17.924] finished loading configuration for server server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:33:17.924] configuring server environment and restoring to previous state server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:17.924] configuring server environment and restoring to previous state server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:33:17.924] configuring server environment and restoring to previous state server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:17.929] acquired exclusive lock on power actions, processing event... action=start lock_id=82ec4f1e-b8a0-11f0-8e84-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:17.929] syncing server configuration with panel server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:17.929] acquired exclusive lock on power actions, processing event... action=start lock_id=82ec58b8-b8a0-11f0-8e84-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:17.929] syncing server configuration with panel server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:17.962] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:17.962] performing server limit modification on-the-fly server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:17.972] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:17.974] performing server limit modification on-the-fly server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:18.030] completed server preflight, starting boot process... server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:18.363] completed server preflight, starting boot process... server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:18.726] starting resource polling for container container_id=d4da20d8-7852-4d3e-adbd-23dabfd365bc environment=docker
 INFO: [Nov  3 11:33:18.945] starting resource polling for container container_id=2e07026f-ef54-4125-ad02-1d4bdf673bd5 environment=docker
 INFO: [Nov  3 11:33:19.048] releasing exclusive lock for power action action=start lock_id=82ec4f1e-b8a0-11f0-8e84-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:19.068] performing server limit modification on-the-fly server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:33:19.132] releasing exclusive lock for power action action=start lock_id=82ec58b8-b8a0-11f0-8e84-c4c6e6686181 server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:19.170] performing server limit modification on-the-fly server=2e07026f-ef54-4125-ad02-1d4bdf673bd5
 INFO: [Nov  3 11:33:19.187] configuring system crons  interval=1m0s subsystem=cron
 INFO: [Nov  3 11:33:19.187] starting cron processes   subsystem=cron
 INFO: [Nov  3 11:33:19.187] configuring internal webserver host_address={redacted} host_port=8080 use_auto_tls=false use_ssl=false
 INFO: [Nov  3 11:33:19.187] updating server states on Panel: marking installing/restoring servers as normal
 INFO: [Nov  3 11:33:19.189] sftp server listening for connections listen={redacted}:2022 public_key=ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIKbmgpOQrRSBPcV6HdGV7q/hhkcaqTfm1qMwRMVkqR4u
 INFO: [Nov  3 11:33:31.226] acquired exclusive lock on power actions, processing event... action=start lock_id=8ad96daf-b8a0-11f0-8e84-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:33:31.226] syncing server configuration with panel server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:33:31.254] performing server limit modification on-the-fly server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:33:31.261] performing server limit modification on-the-fly server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:33:31.441] completed server preflight, starting boot process... server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:33:32.053] starting resource polling for container container_id=a67db310-6b08-4bd6-95d0-8fa0ac094a94 environment=docker
 INFO: [Nov  3 11:33:32.748] releasing exclusive lock for power action action=start lock_id=8ad96daf-b8a0-11f0-8e84-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 11:34:41.678] acquired exclusive lock on power actions, processing event... action=restart lock_id=b4d7816c-b8a0-11f0-8e84-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:34:42.820] syncing server configuration with panel server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:34:42.843] performing server limit modification on-the-fly server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:34:42.849] performing server limit modification on-the-fly server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:34:42.860] completed server preflight, starting boot process... server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:34:43.448] starting resource polling for container container_id=d4da20d8-7852-4d3e-adbd-23dabfd365bc environment=docker
 INFO: [Nov  3 11:34:43.671] releasing exclusive lock for power action action=restart lock_id=b4d7816c-b8a0-11f0-8e84-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:36:50.373] acquired exclusive lock on power actions, processing event... action=restart lock_id=018cd047-b8a1-11f0-8e84-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:36:51.507] syncing server configuration with panel server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:36:51.532] performing server limit modification on-the-fly server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:36:51.537] performing server limit modification on-the-fly server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:36:51.550] completed server preflight, starting boot process... server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
 INFO: [Nov  3 11:36:52.179] starting resource polling for container container_id=d4da20d8-7852-4d3e-adbd-23dabfd365bc environment=docker
 INFO: [Nov  3 11:36:52.378] releasing exclusive lock for power action action=restart lock_id=018cd047-b8a1-11f0-8e84-c4c6e6686181 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc
ERROR: [Nov  3 12:03:12.611] failed to send event over server websocket connection=413d4673-87a5-4a75-964c-ae3c931988b0 error=write tcp 192.168.0.6:8080->192.168.27.65:60410: write: broken pipe event=stats server=d4da20d8-7852-4d3e-adbd-23dabfd365bc subsystem=websocket
Stacktrace:
write tcp 192.168.0.6:8080->192.168.27.65:60410: write: broken pipe
github.com/pelican-dev/wings/router/websocket.(*Handler).listenForServerEvents.func1
    github.com/pelican-dev/wings/router/websocket/listeners.go:100
github.com/pelican-dev/wings/router/websocket.(*Handler).listenForServerEvents
    github.com/pelican-dev/wings/router/websocket/listeners.go:150
github.com/pelican-dev/wings/router/websocket.(*Handler).registerListenerEvents.func1
    github.com/pelican-dev/wings/router/websocket/listeners.go:29
runtime.goexit
    runtime/asm_amd64.s:1700
 WARN: [Nov  3 12:03:12.612] error while processing server event; closing websocket connection connection=413d4673-87a5-4a75-964c-ae3c931988b0 server=d4da20d8-7852-4d3e-adbd-23dabfd365bc subsystem=websocket
ERROR: [Nov  3 12:03:12.612] error closing websocket connection connection=413d4673-87a5-4a75-964c-ae3c931988b0 error=close tcp 192.168.0.6:8080->192.168.27.65:60410: use of closed network connection server=d4da20d8-7852-4d3e-adbd-23dabfd365bc subsystem=websocket

Stacktrace:
close tcp 192.168.0.6:8080->192.168.27.65:60410: use of closed network connection
github.com/pelican-dev/wings/router/websocket.(*Handler).registerListenerEvents.func1
    github.com/pelican-dev/wings/router/websocket/listeners.go:32
runtime.goexit
    runtime/asm_amd64.s:1700
 INFO: [Nov  3 20:11:14.652] acquired exclusive lock on power actions, processing event... action=stop lock_id=de180caf-b8e8-11f0-8e84-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 INFO: [Nov  3 20:11:17.115] releasing exclusive lock for power action action=stop lock_id=de180caf-b8e8-11f0-8e84-c4c6e6686181 server=a67db310-6b08-4bd6-95d0-8fa0ac094a94
 ';

                                            try {
                                                if ($includeEndpoints || $includeLogs) {
                                                    $set('pulled', true);
                                                    $set('log', $output);

                                                    Notification::make()
                                                        ->title('Logs Pulled')
                                                        ->success()
                                                        ->send();
                                                }
                                            } catch (ConnectionException $e) {
                                                Notification::make()
                                                    ->title(trans('admin/node.error_connecting', ['node' => $node->name]))
                                                    ->body($e->getMessage())
                                                    ->danger()
                                                    ->send();

                                            }
                                        }),
                                    Action::make('upload')
                                        ->label(trans('admin/node.diagnostics.upload'))
                                        ->visible(fn (Get $get) => $get('pulled') ?? false)
                                        ->icon('tabler-cloud-upload')->iconButton()->iconSize(IconSize::ExtraLarge),
                                ])
                                ->schema([
                                    Toggle::make('include_endpoints')
                                        ->hintIconTooltip(trans('admin/node.diagnostics.include_endpoints_hint'))
                                        ->hintIcon('tabler-question-mark'),
                                    Toggle::make('include_logs')
                                        ->live()
                                        ->hintIconTooltip(trans('admin/node.diagnostics.include_logs_hint'))
                                        ->hintIcon('tabler-question-mark'),
                                    Slider::make('log_lines')
                                        ->hiddenLabel()
                                        ->tooltips()->fillTrack()->pips()->steppedPips()
                                        ->visible(fn (Get $get) => $get('include_logs'))
                                        ->range(minValue: 100, maxValue: 500)
                                        ->step(50)
                                        ->default(200),
                                    Hidden::make('pulled'),
                                    Hidden::make('uploaded'),
                                ]),
                            Textarea::make('log')
                                ->hiddenLabel()
                                ->columnSpanFull()
                                ->rows('35')
                                ->visible(fn (Get $get) => $get('pulled') ?? false),
                        ]),
                ]),
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $node = Node::findOrFail($data['id']);

        if (!is_ip($node->fqdn)) {
            $ip = get_ip_from_hostname($node->fqdn);
            if ($ip) {
                $data['dns'] = true;
                $data['ip'] = $ip;
            } else {
                $data['dns'] = false;
            }
        }

        return $data;
    }

    protected function getFormActions(): array
    {
        return [];
    }

    /** @return array<Action|Actions> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (Node $node) => $node->servers()->count() > 0)
                ->label(fn (Node $node) => $node->servers()->count() > 0 ? trans('admin/node.node_has_servers') : trans('filament-actions::delete.single.label')),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!$data['behind_proxy']) {
            $data['daemon_listen'] = $data['daemon_connect'];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->fillForm();

        /** @var Node $node */
        $node = $this->record;

        $changed = collect($node->getChanges())->except(['updated_at', 'name', 'tags', 'public', 'maintenance_mode', 'memory', 'memory_overallocate', 'disk', 'disk_overallocate', 'cpu', 'cpu_overallocate'])->all();

        try {
            if ($changed) {
                $this->daemonConfigurationRepository->setNode($node)->update($node);
            }
            parent::getSavedNotification()?->send();
        } catch (ConnectionException) {
            Notification::make()
                ->title(trans('admin/node.error_connecting', ['node' => $node->name]))
                ->body(trans('admin/node.error_connecting_description'))
                ->color('warning')
                ->icon('tabler-database')
                ->warning()
                ->send();
        }
    }

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    protected function getColumnSpan(): ?int
    {
        return null;
    }

    protected function getColumnStart(): ?int
    {
        return null;
    }
}
