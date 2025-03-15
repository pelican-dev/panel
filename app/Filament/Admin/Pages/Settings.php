<?php

namespace App\Filament\Admin\Pages;

use App\Extensions\Captcha\Providers\CaptchaProvider;
use App\Extensions\OAuth\Providers\OAuthProvider;
use App\Models\Backup;
use App\Notifications\MailTested;
use App\Traits\EnvironmentWriterTrait;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification as MailNotification;
use Illuminate\Support\Str;

/**
 * @property Form $form
 */
class Settings extends Page implements HasForms
{
    use EnvironmentWriterTrait;
    use InteractsWithForms;
    use InteractsWithHeaderActions;

    protected static ?string $navigationIcon = 'tabler-settings';

    protected static string $view = 'filament.pages.settings';

    /** @var array<mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('view settings');
    }

    public function getTitle(): string
    {
        return trans('admin/setting.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('admin/setting.title');
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Tabs')
                ->columns()
                ->persistTabInQueryString()
                ->disabled(fn () => !auth()->user()->can('update settings'))
                ->tabs([
                    Tab::make('general')
                        ->label(trans('admin/setting.navigation.general'))
                        ->icon('tabler-home')
                        ->schema($this->generalSettings()),
                    Tab::make('captcha')
                        ->label(trans('admin/setting.navigation.captcha'))
                        ->icon('tabler-shield')
                        ->schema($this->captchaSettings())
                        ->columns(3),
                    Tab::make('mail')
                        ->label(trans('admin/setting.navigation.mail'))
                        ->icon('tabler-mail')
                        ->schema($this->mailSettings()),
                    Tab::make('backup')
                        ->label(trans('admin/setting.navigation.backup'))
                        ->icon('tabler-box')
                        ->schema($this->backupSettings()),
                    Tab::make('OAuth')
                        ->label(trans('admin/setting.navigation.oauth'))
                        ->icon('tabler-brand-oauth')
                        ->schema($this->oauthSettings()),
                    Tab::make('misc')
                        ->label(trans('admin/setting.navigation.misc'))
                        ->icon('tabler-tool')
                        ->schema($this->miscSettings()),
                ]),
        ];
    }

    /** @return Component[] */
    private function generalSettings(): array
    {
        return [
            TextInput::make('APP_NAME')
                ->label(trans('admin/setting.general.app_name'))
                ->required()
                ->default(env('APP_NAME', 'Pelican')),
            TextInput::make('APP_FAVICON')
                ->label(trans('admin/setting.general.app_favicon'))
                ->hintIcon('tabler-question-mark')
                ->hintIconTooltip(trans('admin/setting.general.app_favicon_help'))
                ->required()
                ->default(env('APP_FAVICON', '/pelican.ico')),
            Toggle::make('APP_DEBUG')
                ->label(trans('admin/setting.general.debug_mode'))
                ->inline(false)
                ->onIcon('tabler-check')
                ->offIcon('tabler-x')
                ->onColor('success')
                ->offColor('danger')
                ->formatStateUsing(fn ($state): bool => (bool) $state)
                ->afterStateUpdated(fn ($state, Set $set) => $set('APP_DEBUG', (bool) $state))
                ->default(env('APP_DEBUG', config('app.debug'))),
            ToggleButtons::make('FILAMENT_TOP_NAVIGATION')
                ->label(trans('admin/setting.general.navigation'))
                ->inline()
                ->options([
                    false => trans('admin/setting.general.sidebar'),
                    true => trans('admin/setting.general.topbar'),
                ])
                ->formatStateUsing(fn ($state): bool => (bool) $state)
                ->afterStateUpdated(fn ($state, Set $set) => $set('FILAMENT_TOP_NAVIGATION', (bool) $state))
                ->default(env('FILAMENT_TOP_NAVIGATION', config('panel.filament.top-navigation'))),
            ToggleButtons::make('PANEL_USE_BINARY_PREFIX')
                ->label(trans('admin/setting.general.unit_prefix'))
                ->inline()
                ->options([
                    false => trans('admin/setting.general.decimal_prefix'),
                    true => trans('admin/setting.general.binary_prefix'),
                ])
                ->formatStateUsing(fn ($state): bool => (bool) $state)
                ->afterStateUpdated(fn ($state, Set $set) => $set('PANEL_USE_BINARY_PREFIX', (bool) $state))
                ->default(env('PANEL_USE_BINARY_PREFIX', config('panel.use_binary_prefix'))),
            ToggleButtons::make('APP_2FA_REQUIRED')
                ->label(trans('admin/setting.general.2fa_requirement'))
                ->inline()
                ->options([
                    0 => trans('admin/setting.general.not_required'),
                    1 => trans('admin/setting.general.admins_only'),
                    2 => trans('admin/setting.general.all_users'),
                ])
                ->formatStateUsing(fn ($state): int => (int) $state)
                ->afterStateUpdated(fn ($state, Set $set) => $set('APP_2FA_REQUIRED', (int) $state))
                ->default(env('APP_2FA_REQUIRED', config('panel.auth.2fa_required'))),
            TagsInput::make('TRUSTED_PROXIES')
                ->label(trans('admin/setting.general.trusted_proxies'))
                ->separator()
                ->splitKeys(['Tab', ' '])
                ->placeholder(trans('admin/setting.general.trusted_proxies_help'))
                ->default(env('TRUSTED_PROXIES', implode(',', config('trustedproxy.proxies'))))
                ->hintActions([
                    FormAction::make('clear')
                        ->label(trans('admin/setting.general.clear'))
                        ->color('danger')
                        ->icon('tabler-trash')
                        ->requiresConfirmation()
                        ->authorize(fn () => auth()->user()->can('update settings'))
                        ->action(fn (Set $set) => $set('TRUSTED_PROXIES', [])),
                    FormAction::make('cloudflare')
                        ->label(trans('admin/setting.general.set_to_cf'))
                        ->icon('tabler-brand-cloudflare')
                        ->authorize(fn () => auth()->user()->can('update settings'))
                        ->action(function (Factory $client, Set $set) {
                            $ips = collect();

                            try {
                                $response = $client
                                    ->timeout(3)
                                    ->connectTimeout(3)
                                    ->get('https://api.cloudflare.com/client/v4/ips');

                                if ($response->getStatusCode() === 200) {
                                    $result = $response->json('result');
                                    foreach (['ipv4_cidrs', 'ipv6_cidrs'] as $value) {
                                        $ips->push(...data_get($result, $value));
                                    }
                                    $ips->unique();
                                }
                            } catch (Exception) {
                            }

                            $set('TRUSTED_PROXIES', $ips->values()->all());
                        }),
                ]),
            Select::make('FILAMENT_WIDTH')
                ->label(trans('admin/setting.general.display_width'))
                ->native(false)
                ->options(MaxWidth::class)
                ->selectablePlaceholder(false)
                ->default(env('FILAMENT_WIDTH', config('panel.filament.display-width'))),
        ];
    }

    /**
     * @return Component[]
     */
    private function captchaSettings(): array
    {
        $formFields = [];

        $captchaProviders = CaptchaProvider::get();
        foreach ($captchaProviders as $captchaProvider) {
            $id = Str::upper($captchaProvider->getId());
            $name = Str::title($captchaProvider->getId());

            $formFields[] = Section::make($name)
                ->columns(5)
                ->icon($captchaProvider->getIcon() ?? 'tabler-shield')
                ->collapsed(fn () => !env("CAPTCHA_{$id}_ENABLED", false))
                ->collapsible()
                ->schema([
                    Hidden::make("CAPTCHA_{$id}_ENABLED")
                        ->live()
                        ->default(env("CAPTCHA_{$id}_ENABLED")),
                    Actions::make([
                        FormAction::make("disable_captcha_$id")
                            ->visible(fn (Get $get) => $get("CAPTCHA_{$id}_ENABLED"))
                            ->label(trans('admin/setting.captcha.disable'))
                            ->color('danger')
                            ->action(function (Set $set) use ($id) {
                                $set("CAPTCHA_{$id}_ENABLED", false);
                            }),
                        FormAction::make("enable_captcha_$id")
                            ->visible(fn (Get $get) => !$get("CAPTCHA_{$id}_ENABLED"))
                            ->label(trans('admin/setting.captcha.enable'))
                            ->color('success')
                            ->action(function (Set $set) use ($id, $captchaProviders) {
                                foreach ($captchaProviders as $captchaProvider) {
                                    $loopId = Str::upper($captchaProvider->getId());
                                    $set("CAPTCHA_{$loopId}_ENABLED", $loopId === $id);
                                }
                            }),
                    ])->columnSpan(1),
                    Group::make($captchaProvider->getSettingsForm())
                        ->visible(fn (Get $get) => $get("CAPTCHA_{$id}_ENABLED"))
                        ->columns(4)
                        ->columnSpan(4),
                ]);
        }

        return $formFields;
    }

    /**
     * @return Component[]
     */
    private function mailSettings(): array
    {
        return [
            ToggleButtons::make('MAIL_MAILER')
                ->label(trans('admin/setting.mail.mail_driver'))
                ->columnSpanFull()
                ->inline()
                ->options([
                    'log' => '/storage/logs Directory',
                    'smtp' => 'SMTP Server',
                    'mailgun' => 'Mailgun',
                    'mandrill' => 'Mandrill',
                    'postmark' => 'Postmark',
                    'sendmail' => 'sendmail (PHP)',
                ])
                ->live()
                ->default(env('MAIL_MAILER', config('mail.default')))
                ->hintAction(
                    FormAction::make('test')
                        ->label(trans('admin/setting.mail.test_mail'))
                        ->icon('tabler-send')
                        ->hidden(fn (Get $get) => $get('MAIL_MAILER') === 'log')
                        ->authorize(fn () => auth()->user()->can('update settings'))
                        ->action(function (Get $get) {
                            // Store original mail configuration
                            $originalConfig = [
                                'mail.default' => config('mail.default'),
                                'mail.mailers.smtp.host' => config('mail.mailers.smtp.host'),
                                'mail.mailers.smtp.port' => config('mail.mailers.smtp.port'),
                                'mail.mailers.smtp.username' => config('mail.mailers.smtp.username'),
                                'mail.mailers.smtp.password' => config('mail.mailers.smtp.password'),
                                'mail.mailers.smtp.encryption' => config('mail.mailers.smtp.encryption'),
                                'mail.from.address' => config('mail.from.address'),
                                'mail.from.name' => config('mail.from.name'),
                                'services.mailgun.domain' => config('services.mailgun.domain'),
                                'services.mailgun.secret' => config('services.mailgun.secret'),
                                'services.mailgun.endpoint' => config('services.mailgun.endpoint'),
                            ];

                            try {
                                // Update mail configuration dynamically
                                config([
                                    'mail.default' => $get('MAIL_MAILER'),
                                    'mail.mailers.smtp.host' => $get('MAIL_HOST'),
                                    'mail.mailers.smtp.port' => $get('MAIL_PORT'),
                                    'mail.mailers.smtp.username' => $get('MAIL_USERNAME'),
                                    'mail.mailers.smtp.password' => $get('MAIL_PASSWORD'),
                                    'mail.mailers.smtp.encryption' => $get('MAIL_SCHEME'),
                                    'mail.from.address' => $get('MAIL_FROM_ADDRESS'),
                                    'mail.from.name' => $get('MAIL_FROM_NAME'),
                                    'services.mailgun.domain' => $get('MAILGUN_DOMAIN'),
                                    'services.mailgun.secret' => $get('MAILGUN_SECRET'),
                                    'services.mailgun.endpoint' => $get('MAILGUN_ENDPOINT'),
                                ]);

                                MailNotification::route('mail', auth()->user()->email)
                                    ->notify(new MailTested(auth()->user()));

                                Notification::make()
                                    ->title(trans('admin/setting.mail.test_mail_sent'))
                                    ->success()
                                    ->send();
                            } catch (Exception $exception) {
                                Notification::make()
                                    ->title(trans('admin/setting.mail.test_mail_failed'))
                                    ->body($exception->getMessage())
                                    ->danger()
                                    ->send();
                            } finally {
                                config($originalConfig);
                            }
                        })
                ),
            Section::make(trans('admin/setting.mail.from_settings'))
                ->description(trans('admin/setting.mail.from_settings_help'))
                ->columns()
                ->schema([
                    TextInput::make('MAIL_FROM_ADDRESS')
                        ->label(trans('admin/setting.mail.from_address'))
                        ->required()
                        ->email()
                        ->default(env('MAIL_FROM_ADDRESS', config('mail.from.address'))),
                    TextInput::make('MAIL_FROM_NAME')
                        ->label(trans('admin/setting.mail.from_name'))
                        ->required()
                        ->default(env('MAIL_FROM_NAME', config('mail.from.name'))),
                ]),
            Section::make(trans('admin/setting.mail.smtp.smtp_title'))
                ->columns()
                ->visible(fn (Get $get) => $get('MAIL_MAILER') === 'smtp')
                ->schema([
                    TextInput::make('MAIL_HOST')
                        ->label(trans('admin/setting.mail.smtp.host'))
                        ->required()
                        ->default(env('MAIL_HOST', config('mail.mailers.smtp.host'))),
                    TextInput::make('MAIL_PORT')
                        ->label(trans('admin/setting.mail.smtp.port'))
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(65535)
                        ->default(env('MAIL_PORT', config('mail.mailers.smtp.port'))),
                    TextInput::make('MAIL_USERNAME')
                        ->label(trans('admin/setting.mail.smtp.username'))
                        ->default(env('MAIL_USERNAME', config('mail.mailers.smtp.username'))),
                    TextInput::make('MAIL_PASSWORD')
                        ->label(trans('admin/setting.mail.smtp.password'))
                        ->password()
                        ->revealable()
                        ->default(env('MAIL_PASSWORD')),
                    ToggleButtons::make('MAIL_SCHEME')
                        ->label(trans('admin/setting.mail.smtp.encryption'))
                        ->inline()
                        ->options([
                            'tls' => trans('admin/setting.mail.smtp.tls'),
                            'ssl' => trans('admin/setting.mail.smtp.ssl'),
                            '' => trans('admin/setting.mail.smtp.none'),
                        ])
                        ->default(env('MAIL_SCHEME', config('mail.mailers.smtp.encryption', 'tls')))
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            $port = match ($state) {
                                'tls' => 587,
                                'ssl' => 465,
                                default => 25,
                            };
                            $set('MAIL_PORT', $port);
                        }),
                ]),
            Section::make(trans('admin/setting.mail.mailgun.mailgun_title'))
                ->columns()
                ->visible(fn (Get $get) => $get('MAIL_MAILER') === 'mailgun')
                ->schema([
                    TextInput::make('MAILGUN_DOMAIN')
                        ->label(trans('admin/setting.mail.mailgun.domain'))
                        ->required()
                        ->default(env('MAILGUN_DOMAIN', config('services.mailgun.domain'))),
                    TextInput::make('MAILGUN_SECRET')
                        ->label(trans('admin/setting.mail.mailgun.secret'))
                        ->required()
                        ->default(env('MAILGUN_SECRET', config('services.mailgun.secret'))),
                    TextInput::make('MAILGUN_ENDPOINT')
                        ->label(trans('admin/setting.mail.mailgun.endpoint'))
                        ->required()
                        ->default(env('MAILGUN_ENDPOINT', config('services.mailgun.endpoint'))),
                ]),
        ];
    }

    /**
     * @return Component[]
     */
    private function backupSettings(): array
    {
        return [
            ToggleButtons::make('APP_BACKUP_DRIVER')
                ->label(trans('admin/setting.backup.backup_driver'))
                ->columnSpanFull()
                ->inline()
                ->options([
                    Backup::ADAPTER_DAEMON => 'Wings',
                    Backup::ADAPTER_AWS_S3 => 'S3',
                ])
                ->live()
                ->default(env('APP_BACKUP_DRIVER', config('backups.default'))),
            Section::make(trans('admin/setting.backup.throttle'))
                ->description(trans('admin/setting.backup.throttle_help'))
                ->columns()
                ->schema([
                    TextInput::make('BACKUP_THROTTLE_LIMIT')
                        ->label(trans('admin/setting.backup.limit'))
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->default(config('backups.throttles.limit')),
                    TextInput::make('BACKUP_THROTTLE_PERIOD')
                        ->label(trans('admin/setting.backup.period'))
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->suffix('Seconds')
                        ->default(config('backups.throttles.period')),
                ]),
            Section::make(trans('admin/setting.backup.s3.s3_title'))
                ->columns()
                ->visible(fn (Get $get) => $get('APP_BACKUP_DRIVER') === Backup::ADAPTER_AWS_S3)
                ->schema([
                    TextInput::make('AWS_DEFAULT_REGION')
                        ->label(trans('admin/setting.backup.s3.default_region'))
                        ->required()
                        ->default(config('backups.disks.s3.region')),
                    TextInput::make('AWS_ACCESS_KEY_ID')
                        ->label(trans('admin/setting.backup.s3.access_key'))
                        ->required()
                        ->default(config('backups.disks.s3.key')),
                    TextInput::make('AWS_SECRET_ACCESS_KEY')
                        ->label(trans('admin/setting.backup.s3.secret_key'))
                        ->required()
                        ->default(config('backups.disks.s3.secret')),
                    TextInput::make('AWS_BACKUPS_BUCKET')
                        ->label(trans('admin/setting.backup.s3.bucket'))
                        ->required()
                        ->default(config('backups.disks.s3.bucket')),
                    TextInput::make('AWS_ENDPOINT')
                        ->label(trans('admin/setting.backup.s3.endpoint'))
                        ->required()
                        ->default(config('backups.disks.s3.endpoint')),
                    Toggle::make('AWS_USE_PATH_STYLE_ENDPOINT')
                        ->label(trans('admin/setting.backup.s3.use_path_style_endpoint'))
                        ->inline(false)
                        ->onIcon('tabler-check')
                        ->offIcon('tabler-x')
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('AWS_USE_PATH_STYLE_ENDPOINT', (bool) $state))
                        ->default(env('AWS_USE_PATH_STYLE_ENDPOINT', config('backups.disks.s3.use_path_style_endpoint'))),
                ]),
        ];
    }

    /**
     * @return Component[]
     */
    private function oauthSettings(): array
    {
        $formFields = [];

        $oauthProviders = OAuthProvider::get();
        foreach ($oauthProviders as $oauthProvider) {
            $id = Str::upper($oauthProvider->getId());
            $name = Str::title($oauthProvider->getId());

            $formFields[] = Section::make($name)
                ->columns(5)
                ->icon($oauthProvider->getIcon() ?? 'tabler-brand-oauth')
                ->collapsed(fn () => !env("OAUTH_{$id}_ENABLED", false))
                ->collapsible()
                ->schema([
                    Hidden::make("OAUTH_{$id}_ENABLED")
                        ->live()
                        ->default(env("OAUTH_{$id}_ENABLED")),
                    Actions::make([
                        FormAction::make("disable_oauth_$id")
                            ->visible(fn (Get $get) => $get("OAUTH_{$id}_ENABLED"))
                            ->label(trans('admin/setting.oauth.disable'))
                            ->color('danger')
                            ->action(function (Set $set) use ($id) {
                                $set("OAUTH_{$id}_ENABLED", false);
                            }),
                        FormAction::make("enable_oauth_$id")
                            ->visible(fn (Get $get) => !$get("OAUTH_{$id}_ENABLED"))
                            ->label(trans('admin/setting.oauth.enable'))
                            ->color('success')
                            ->steps($oauthProvider->getSetupSteps())
                            ->modalHeading(trans('admin/setting.oauth.enable') . ' ' . $name)
                            ->modalSubmitActionLabel(trans('admin/setting.oauth.enable'))
                            ->modalCancelAction(false)
                            ->action(function ($data, Set $set) use ($id) {
                                $data = array_merge([
                                    "OAUTH_{$id}_ENABLED" => 'true',
                                ], $data);

                                foreach ($data as $key => $value) {
                                    $set($key, $value);
                                }
                            }),
                    ])->columnSpan(1),
                    Group::make($oauthProvider->getSettingsForm())
                        ->visible(fn (Get $get) => $get("OAUTH_{$id}_ENABLED"))
                        ->columns(4)
                        ->columnSpan(4),
                ]);
        }

        return $formFields;
    }

    /**
     * @return Component[]
     */
    private function miscSettings(): array
    {
        return [
            Section::make(trans('admin/setting.misc.auto_allocation.title'))
                ->description(trans('admin/setting.misc.auto_allocation.helper'))
                ->columns()
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('PANEL_CLIENT_ALLOCATIONS_ENABLED')
                        ->label(trans('admin/setting.misc.auto_allocation.question'))
                        ->onIcon('tabler-check')
                        ->offIcon('tabler-x')
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->columnSpanFull()
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('PANEL_CLIENT_ALLOCATIONS_ENABLED', (bool) $state))
                        ->default(env('PANEL_CLIENT_ALLOCATIONS_ENABLED', config('panel.client_features.allocations.enabled'))),
                    TextInput::make('PANEL_CLIENT_ALLOCATIONS_RANGE_START')
                        ->label(trans('admin/setting.misc.auto_allocation.start'))
                        ->required()
                        ->numeric()
                        ->minValue(1024)
                        ->maxValue(65535)
                        ->visible(fn (Get $get) => $get('PANEL_CLIENT_ALLOCATIONS_ENABLED'))
                        ->default(env('PANEL_CLIENT_ALLOCATIONS_RANGE_START')),
                    TextInput::make('PANEL_CLIENT_ALLOCATIONS_RANGE_END')
                        ->label(trans('admin/setting.misc.auto_allocation.end'))
                        ->required()
                        ->numeric()
                        ->minValue(1024)
                        ->maxValue(65535)
                        ->visible(fn (Get $get) => $get('PANEL_CLIENT_ALLOCATIONS_ENABLED'))
                        ->default(env('PANEL_CLIENT_ALLOCATIONS_RANGE_END')),
                ]),
            Section::make(trans('admin/setting.misc.mail_notifications.title'))
                ->description(trans('admin/setting.misc.mail_notifications.helper'))
                ->columns()
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('PANEL_SEND_INSTALL_NOTIFICATION')
                        ->label(trans('admin/setting.misc.mail_notifications.server_installed'))
                        ->onIcon('tabler-check')
                        ->offIcon('tabler-x')
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->columnSpanFull()
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('PANEL_SEND_INSTALL_NOTIFICATION', (bool) $state))
                        ->default(env('PANEL_SEND_INSTALL_NOTIFICATION', config('panel.email.send_install_notification'))),
                    Toggle::make('PANEL_SEND_REINSTALL_NOTIFICATION')
                        ->label(trans('admin/setting.misc.mail_notifications.server_reinstalled'))
                        ->onIcon('tabler-check')
                        ->offIcon('tabler-x')
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->columnSpanFull()
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('PANEL_SEND_REINSTALL_NOTIFICATION', (bool) $state))
                        ->default(env('PANEL_SEND_REINSTALL_NOTIFICATION', config('panel.email.send_reinstall_notification'))),
                ]),
            Section::make(trans('admin/setting.misc.connections.title'))
                ->description(trans('admin/setting.misc.connections.helper'))
                ->columns()
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextInput::make('GUZZLE_TIMEOUT')
                        ->label(trans('admin/setting.misc.connections.request_timeout'))
                        ->required()
                        ->numeric()
                        ->minValue(15)
                        ->maxValue(60)
                        ->suffix(trans('admin/setting.misc.connections.seconds'))
                        ->default(env('GUZZLE_TIMEOUT', config('panel.guzzle.timeout'))),
                    TextInput::make('GUZZLE_CONNECT_TIMEOUT')
                        ->label(trans('admin/setting.misc.connections.connection_timeout'))
                        ->required()
                        ->numeric()
                        ->minValue(5)
                        ->maxValue(60)
                        ->suffix(trans('admin/setting.misc.connections.seconds'))
                        ->default(env('GUZZLE_CONNECT_TIMEOUT', config('panel.guzzle.connect_timeout'))),
                ]),
            Section::make(trans('admin/setting.misc.activity_log.title'))
                ->description(trans('admin/setting.misc.activity_log.helper'))
                ->columns()
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextInput::make('APP_ACTIVITY_PRUNE_DAYS')
                        ->label(trans('admin/setting.misc.activity_log.prune_age'))
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(365)
                        ->suffix(trans('admin/setting.misc.activity_log.days'))
                        ->default(env('APP_ACTIVITY_PRUNE_DAYS', config('activity.prune_days'))),
                    Toggle::make('APP_ACTIVITY_HIDE_ADMIN')
                        ->label(trans('admin/setting.misc.activity_log.log_admin'))
                        ->inline(false)
                        ->onIcon('tabler-check')
                        ->offIcon('tabler-x')
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('APP_ACTIVITY_HIDE_ADMIN', (bool) $state))
                        ->default(env('APP_ACTIVITY_HIDE_ADMIN', config('activity.hide_admin_activity'))),
                ]),
            Section::make(trans('admin/setting.misc.api.title'))
                ->description(trans('admin/setting.misc.api.helper'))
                ->columns()
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextInput::make('APP_API_CLIENT_RATELIMIT')
                        ->label(trans('admin/setting.misc.api.client_rate'))
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->suffix(trans('admin/setting.misc.api.rpm'))
                        ->default(env('APP_API_CLIENT_RATELIMIT', config('http.rate_limit.client'))),
                    TextInput::make('APP_API_APPLICATION_RATELIMIT')
                        ->label(trans('admin/setting.misc.api.app_rate'))
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->suffix(trans('admin/setting.misc.api.rpm'))
                        ->default(env('APP_API_APPLICATION_RATELIMIT', config('http.rate_limit.application'))),
                ]),
            Section::make(trans('admin/setting.misc.server.title'))
                ->description(trans('admin/setting.misc.server.helper'))
                ->columns()
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('PANEL_EDITABLE_SERVER_DESCRIPTIONS')
                        ->label(trans('admin/setting.misc.server.edit_server_desc'))
                        ->onIcon('tabler-check')
                        ->offIcon('tabler-x')
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->columnSpanFull()
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('PANEL_EDITABLE_SERVER_DESCRIPTIONS', (bool) $state))
                        ->default(env('PANEL_EDITABLE_SERVER_DESCRIPTIONS', config('panel.editable_server_descriptions'))),
                ]),
            Section::make(trans('admin/setting.misc.webhook.title'))
                ->description(trans('admin/setting.misc.webhook.helper'))
                ->columns()
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextInput::make('APP_WEBHOOK_PRUNE_DAYS')
                        ->label(trans('admin/setting.misc.webhook.prune_age'))
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(365)
                        ->suffix(trans('admin/setting.misc.webhook.days'))
                        ->default(env('APP_WEBHOOK_PRUNE_DAYS', config('panel.webhook.prune_days'))),
                ]),
        ];
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            // Convert bools to a string, so they are correctly written to the .env file
            $data = array_map(fn ($value) => is_bool($value) ? ($value ? 'true' : 'false') : $value, $data);

            $this->writeToEnvironment($data);

            Artisan::call('config:clear');
            Artisan::call('queue:restart');

            $this->redirect($this->getUrl());

            Notification::make()
                ->title(trans('admin/setting.save_success'))
                ->success()
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->title(trans('admin/setting.save_failed'))
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->action('save')
                ->authorize(fn () => auth()->user()->can('update settings'))
                ->keyBindings(['mod+s']),
        ];

    }
}
