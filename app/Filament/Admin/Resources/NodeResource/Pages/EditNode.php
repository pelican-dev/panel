<?php

namespace App\Filament\Admin\Resources\NodeResource\Pages;

use App\Filament\Admin\Resources\NodeResource;
use App\Models\Node;
use App\Repositories\Daemon\DaemonConfigurationRepository;
use App\Services\Helpers\SoftwareVersionService;
use App\Services\Nodes\NodeAutoDeployService;
use App\Services\Nodes\NodeUpdateService;
use Exception;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Actions as FormActions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\View;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\HtmlString;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class EditNode extends EditRecord
{
    protected static string $resource = NodeResource::class;

    private DaemonConfigurationRepository $daemonConfigurationRepository;

    private NodeUpdateService $nodeUpdateService;

    public function boot(DaemonConfigurationRepository $daemonConfigurationRepository, NodeUpdateService $nodeUpdateService): void
    {
        $this->daemonConfigurationRepository = $daemonConfigurationRepository;
        $this->nodeUpdateService = $nodeUpdateService;
    }

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
                    Tab::make('')
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
                                ->schema([
                                    Placeholder::make('')
                                        ->label(trans('admin/node.wings_version'))
                                        ->content(fn (Node $node, SoftwareVersionService $versionService) => ($node->systemInformation()['version'] ?? trans('admin/node.unknown')) . ' (' . trans('admin/node.latest') . ': ' . $versionService->latestWingsVersion() . ')'),
                                    Placeholder::make('')
                                        ->label(trans('admin/node.cpu_threads'))
                                        ->content(fn (Node $node) => $node->systemInformation()['cpu_count'] ?? 0),
                                    Placeholder::make('')
                                        ->label(trans('admin/node.architecture'))
                                        ->content(fn (Node $node) => $node->systemInformation()['architecture'] ?? trans('admin/node.unknown')),
                                    Placeholder::make('')
                                        ->label(trans('admin/node.kernel'))
                                        ->content(fn (Node $node) => $node->systemInformation()['kernel_version'] ?? trans('admin/node.unknown')),
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
                    Tab::make(trans('admin/node.tabs.basic_settings'))
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

                                    $validRecords = gethostbynamel($state);
                                    if ($validRecords) {
                                        $set('dns', true);

                                        $set('ip', collect($validRecords)->first());

                                        return;
                                    }

                                    $set('dns', false);
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
                                ->options([
                                    true => trans('admin/node.valid'),
                                    false => trans('admin/node.invalid'),
                                ])
                                ->colors([
                                    true => 'success',
                                    false => 'danger',
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
                    Tab::make('adv')
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
                                ->hintAction(fn () => request()->isSecure() ? CopyAction::make() : null)
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
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip(trans('admin/node.upload_limit_help.0') . trans('admin/node.upload_limit_help.1'))
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
                                ->label(trans('admin/node.use_for_deploy'))->inline()
                                ->options([
                                    true => trans('admin/node.yes'),
                                    false => trans('admin/node.no'),
                                ])
                                ->colors([
                                    true => 'success',
                                    false => 'danger',
                                ]),
                            ToggleButtons::make('maintenance_mode')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 3,
                                ])
                                ->label(trans('admin/node.maintenance_mode'))->inline()
                                ->hinticon('tabler-question-mark')
                                ->hintIconTooltip(trans('admin/node.maintenance_mode_help'))
                                ->options([
                                    true => trans('admin/node.enabled'),
                                    false => trans('admin/node.disabled'),
                                ])
                                ->colors([
                                    false => 'success',
                                    true => 'danger',
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
                                        ->options([
                                            true => trans('admin/node.unlimited'),
                                            false => trans('admin/node.limited'),
                                        ])
                                        ->colors([
                                            true => 'primary',
                                            false => 'warning',
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
                                        ->options([
                                            true => trans('admin/node.unlimited'),
                                            false => trans('admin/node.limited'),
                                        ])
                                        ->colors([
                                            true => 'primary',
                                            false => 'warning',
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
                                        ->options([
                                            true => trans('admin/node.unlimited'),
                                            false => trans('admin/node.limited'),
                                        ])
                                        ->colors([
                                            true => 'primary',
                                            false => 'warning',
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
                    Tab::make('Config')
                        ->label(trans('admin/node.tabs.config_file'))
                        ->icon('tabler-code')
                        ->schema([
                            Placeholder::make('instructions')
                                ->label(trans('admin/node.instructions'))
                                ->columnSpanFull()
                                ->content(new HtmlString(trans('admin/node.instructions_help'))),
                            Textarea::make('config')
                                ->label('/etc/pelican/config.yml')
                                ->disabled()
                                ->rows(19)
                                ->hintAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                                ->columnSpanFull(),
                            Grid::make()
                                ->columns()
                                ->schema([
                                    FormActions::make([
                                        FormActions\Action::make('autoDeploy')
                                            ->label(trans('admin/node.auto_deploy'))
                                            ->color('primary')
                                            ->modalHeading(trans('admin/node.auto_deploy'))
                                            ->icon('tabler-rocket')
                                            ->modalSubmitAction(false)
                                            ->modalCancelAction(false)
                                            ->modalFooterActionsAlignment(Alignment::Center)
                                            ->form([
                                                ToggleButtons::make('docker')
                                                    ->label('Type')
                                                    ->live()
                                                    ->helperText(trans('admin/node.auto_question'))
                                                    ->inline()
                                                    ->default(false)
                                                    ->afterStateUpdated(fn (bool $state, NodeAutoDeployService $service, Node $node, Set $set) => $set('generatedToken', $service->handle(request(), $node, $state)))
                                                    ->options([
                                                        false => trans('admin/node.standalone'),
                                                        true => trans('admin/node.docker'),
                                                    ])
                                                    ->colors([
                                                        false => 'primary',
                                                        true => 'success',
                                                    ])
                                                    ->columnSpan(1),
                                                Textarea::make('generatedToken')
                                                    ->label(trans('admin/node.auto_command'))
                                                    ->readOnly()
                                                    ->autosize()
                                                    ->hintAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                                                    ->formatStateUsing(fn (NodeAutoDeployService $service, Node $node, Set $set, Get $get) => $set('generatedToken', $service->handle(request(), $node, $get('docker')))),
                                            ])
                                            ->mountUsing(function (Forms\Form $form) {
                                                $form->fill();
                                            }),
                                    ])->fullWidth(),
                                    FormActions::make([
                                        FormActions\Action::make('resetKey')
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
                ]),
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $node = Node::findOrFail($data['id']);

        $data['config'] = $node->getYamlConfiguration();

        if (!is_ip($node->fqdn)) {
            $validRecords = gethostbynamel($node->fqdn);
            if ($validRecords) {
                $data['dns'] = true;
                $data['ip'] = collect($validRecords)->first();
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
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
