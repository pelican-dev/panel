<?php

namespace App\Filament\Admin\Pages;

use App\Extensions\Avatar\AvatarService;
use App\Extensions\Captcha\CaptchaService;
use App\Extensions\OAuth\OAuthService;
use App\Models\Backup;
use App\Notifications\MailTested;
use App\Traits\EnvironmentWriterTrait;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use BackedEnum;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Width;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification as MailNotification;
use Illuminate\Support\Str;

/**
 * @property Schema $form
 */
class Settings extends Page implements HasSchemas
{
    use CanCustomizeHeaderActions, InteractsWithHeaderActions {
        CanCustomizeHeaderActions::getHeaderActions insteadof InteractsWithHeaderActions;
    }
    use CanCustomizeHeaderWidgets;
    use EnvironmentWriterTrait;
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-settings';

    protected string $view = 'filament.pages.settings';

    protected OAuthService $oauthService;

    protected AvatarService $avatarService;

    protected CaptchaService $captchaService;

    /** @var array<mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function boot(OAuthService $oauthService, AvatarService $avatarService, CaptchaService $captchaService): void
    {
        $this->oauthService = $oauthService;
        $this->avatarService = $avatarService;
        $this->captchaService = $captchaService;
    }

    public static function canAccess(): bool
    {
        return user()?->can('view settings');
    }

    public function getTitle(): string
    {
        return trans('admin/setting.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('admin/setting.title');
    }

    /**
     * @return array<Component>
     *
     * @throws Exception
     */
    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Tabs')
                ->columns()
                ->persistTabInQueryString()
                ->disabled(fn () => !user()?->can('update settings'))
                ->tabs([
                    Tab::make('general')
                        ->label(trans('admin/setting.navigation.general'))
                        ->icon('tabler-home')
                        ->schema($this->generalSettings()),
                    Tab::make('captcha')
                        ->label(trans('admin/setting.navigation.captcha'))
                        ->icon('tabler-shield')
                        ->schema($this->captchaSettings())
                        ->columns(1),
                    Tab::make('mail')
                        ->label(trans('admin/setting.navigation.mail'))
                        ->icon('tabler-mail')
                        ->schema($this->mailSettings()),
                    Tab::make('backup')
                        ->label(trans('admin/setting.navigation.backup'))
                        ->icon('tabler-box')
                        ->schema($this->backupSettings()),
                    Tab::make('oauth')
                        ->label(trans('admin/setting.navigation.oauth'))
                        ->icon('tabler-brand-oauth')
                        ->schema($this->oauthSettings())
                        ->columns(1),
                    Tab::make('misc')
                        ->label(trans('admin/setting.navigation.misc'))
                        ->icon('tabler-tool')
                        ->schema($this->miscSettings()),
                ]),
        ];
    }

    /** @return Component[]
     * @throws Exception
     */
    private function generalSettings(): array
    {
        return [
            TextInput::make('APP_NAME')
                ->label(trans('admin/setting.general.app_name'))
                ->required()
                ->default(env('APP_NAME', 'Pelican')),
            Group::make()
                ->columns(2)
                ->schema([
                    TextInput::make('APP_LOGO')
                        ->label(trans('admin/setting.general.app_logo'))
                        ->hintIcon('tabler-question-mark', trans('admin/setting.general.app_logo_help'))
                        ->default(env('APP_LOGO'))
                        ->placeholder('/pelican.svg'),
                    TextInput::make('APP_FAVICON')
                        ->label(trans('admin/setting.general.app_favicon'))
                        ->hintIcon('tabler-question-mark', trans('admin/setting.general.app_favicon_help'))
                        ->required()
                        ->default(env('APP_FAVICON', '/pelican.ico'))
                        ->placeholder('/pelican.ico'),
                ]),
            Group::make()
                ->columns(2)
                ->schema([
                    Toggle::make('APP_DEBUG')
                        ->label(trans('admin/setting.general.debug_mode'))
                        ->inline(false)
                        ->onIcon('tabler-check')
                        ->offIcon('tabler-x')
                        ->onColor('success')
                        ->offColor('danger')
                        ->stateCast(new BooleanStateCast(false))
                        ->default(env('APP_DEBUG', config('app.debug'))),
                ]),
            Group::make()
                ->columns(2)
                ->schema([
                    Select::make('FILAMENT_AVATAR_PROVIDER')
                        ->label(trans('admin/setting.general.avatar_provider'))
                        ->options($this->avatarService->getMappings())
                        ->selectablePlaceholder(false)
                        ->default(env('FILAMENT_AVATAR_PROVIDER', config('panel.filament.avatar-provider'))),
                    Toggle::make('FILAMENT_UPLOADABLE_AVATARS')
                        ->label(trans('admin/setting.general.uploadable_avatars'))
                        ->inline(false)
                        ->onIcon('tabler-check')
                        ->offIcon('tabler-x')
                        ->onColor('success')
                        ->offColor('danger')
                        ->stateCast(new BooleanStateCast(false))
                        ->default(env('FILAMENT_UPLOADABLE_AVATARS', config('panel.filament.uploadable-avatars'))),
                ]),
            ToggleButtons::make('PANEL_USE_BINARY_PREFIX')
                ->label(trans('admin/setting.general.unit_prefix'))
                ->inline()
                ->options([
                    0 => trans('admin/setting.general.decimal_prefix'),
                    1 => trans('admin/setting.general.binary_prefix'),
                ])
                ->stateCast(new BooleanStateCast(false, true))
                ->default(env('PANEL_USE_BINARY_PREFIX', config('panel.use_binary_prefix'))),
            ToggleButtons::make('FILAMENT_DEFAULT_NAVIGATION')
                ->label(trans('admin/setting.general.default_navigation'))
                ->inline()
                ->options([
                    'sidebar' => trans('admin/setting.general.sidebar'),
                    'topbar' => trans('admin/setting.general.topbar'),
                    'mixed' => trans('admin/setting.general.mixed'),
                ])
                ->default(env('FILAMENT_DEFAULT_NAVIGATION', config('panel.filament.default-navigation'))),
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
            Select::make('FILAMENT_WIDTH')
                ->label(trans('admin/setting.general.display_width'))
                ->options(Width::class)
                ->selectablePlaceholder(false)
                ->default(env('FILAMENT_WIDTH', config('panel.filament.display-width'))),
            TagsInput::make('TRUSTED_PROXIES')
                ->label(trans('admin/setting.general.trusted_proxies'))
                ->separator()
                ->splitKeys(['Tab', ' '])
                ->placeholder(trans('admin/setting.general.trusted_proxies_help'))
                ->default(env('TRUSTED_PROXIES', implode(',', Arr::wrap(config('trustedproxy.proxies')))))
                ->hintActions([
                    Action::make('clear')
                        ->label(trans('admin/setting.general.clear'))
                        ->color('danger')
                        ->icon('tabler-trash')
                        ->requiresConfirmation()
                        ->authorize(fn () => user()?->can('update settings'))
                        ->action(fn (Set $set) => $set('TRUSTED_PROXIES', [])),
                    Action::make('cloudflare')
                        ->label(trans('admin/setting.general.set_to_cf'))
                        ->icon('tabler-brand-cloudflare')
                        ->authorize(fn () => user()?->can('update settings'))
                        ->action(function (Factory $client, Set $set) {
                            $ips = collect();

                            try {
                                $response = $client
                                    ->timeout(3)
                                    ->connectTimeout(3)
                                    ->get('https://api.cloudflare.com/client/v4/ips');

                                if ($response->status() === 200) {
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
        ];
    }

    /**
     * @return Component[]
     *
     * @throws Exception
     */
    private function captchaSettings(): array
    {
        $formFields = [];

        $captchaSchemas = $this->captchaService->getAll();
        foreach ($captchaSchemas as $schema) {
            $id = Str::upper($schema->getId());

            $formFields[] = Section::make($schema->getName())
                ->columns(5)
                ->icon($schema->getIcon() ?? 'tabler-shield')
                ->collapsed(fn () => !$schema->isEnabled())
                ->collapsible()
                ->schema([
                    Hidden::make("CAPTCHA_{$id}_ENABLED")
                        ->live()
                        ->default(env("CAPTCHA_{$id}_ENABLED")),
                    Actions::make([
                        Action::make("disable_captcha_$id")
                            ->visible(fn (Get $get) => $get("CAPTCHA_{$id}_ENABLED"))
                            ->disabled(fn () => !user()?->can('update settings'))
                            ->label(trans('admin/setting.captcha.disable'))
                            ->color('danger')
                            ->action(fn (Set $set) => $set("CAPTCHA_{$id}_ENABLED", false)),
                        Action::make("enable_captcha_$id")
                            ->visible(fn (Get $get) => !$get("CAPTCHA_{$id}_ENABLED"))
                            ->disabled(fn () => !user()?->can('update settings'))
                            ->label(trans('admin/setting.captcha.enable'))
                            ->color('success')
                            ->action(fn (Set $set) => $set("CAPTCHA_{$id}_ENABLED", true)),
                    ])->columnSpan(1),
                    Group::make($schema->getSettingsForm())
                        ->visible(fn (Get $get) => $get("CAPTCHA_{$id}_ENABLED"))
                        ->columns(4)
                        ->columnSpan(4),
                ]);
        }

        return $formFields;
    }

    /**
     * @return Component[]
     *
     * @throws Exception
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
                    Action::make('test')
                        ->label(trans('admin/setting.mail.test_mail'))
                        ->icon('tabler-send')
                        ->hidden(fn (Get $get) => $get('MAIL_MAILER') === 'log')
                        ->authorize(fn () => user()?->can('update settings'))
                        ->action(function (Get $get) {
                            // Store original mail configuration
                            $originalConfig = [
                                'mail.default' => config('mail.default'),
                                'mail.mailers.smtp.host' => config('mail.mailers.smtp.host'),
                                'mail.mailers.smtp.port' => config('mail.mailers.smtp.port'),
                                'mail.mailers.smtp.username' => config('mail.mailers.smtp.username'),
                                'mail.mailers.smtp.password' => config('mail.mailers.smtp.password'),
                                'mail.mailers.smtp.scheme' => config('mail.mailers.smtp.scheme'),
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
                                    'mail.mailers.smtp.scheme' => $get('MAIL_SCHEME'),
                                    'mail.from.address' => $get('MAIL_FROM_ADDRESS'),
                                    'mail.from.name' => $get('MAIL_FROM_NAME'),
                                    'services.mailgun.domain' => $get('MAILGUN_DOMAIN'),
                                    'services.mailgun.secret' => $get('MAILGUN_SECRET'),
                                    'services.mailgun.endpoint' => $get('MAILGUN_ENDPOINT'),
                                ]);

                                MailNotification::route('mail', user()?->email)
                                    ->notify(new MailTested(user()));

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
                ->columnSpanFull()
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
                ->columnSpanFull()
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
                        ->label(trans('admin/setting.mail.smtp.scheme'))
                        ->inline()
                        ->options([
                            'smtp' => 'SMTP',
                            'smtps' => 'SMTPS',
                        ])
                        ->default(env('MAIL_SCHEME', config('mail.mailers.smtp.scheme')))
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            $set('MAIL_PORT', $state === 'smtps' ? 587 : 2525);
                        }),
                ]),
            Section::make(trans('admin/setting.mail.mailgun.mailgun_title'))
                ->columns()
                ->columnSpanFull()
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
     *
     * @throws Exception
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
                ->columnSpanFull()
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
                        ->stateCast(new BooleanStateCast(false))
                        ->default(env('AWS_USE_PATH_STYLE_ENDPOINT', config('backups.disks.s3.use_path_style_endpoint'))),
                ]),
        ];
    }

    /**
     * @return Component[]
     *
     * @throws Exception
     */
    private function oauthSettings(): array
    {
        $formFields = [];

        $oauthSchemas = $this->oauthService->getAll();
        foreach ($oauthSchemas as $schema) {
            $id = Str::upper($schema->getId());
            $key = $schema->getConfigKey();

            $formFields[] = Section::make($schema->getName())
                ->columns(5)
                ->icon($schema->getIcon() ?? 'tabler-brand-oauth')
                ->collapsed(fn () => !env($key, false))
                ->collapsible()
                ->schema([
                    Hidden::make($key)
                        ->live()
                        ->default(env($key)),
                    Actions::make([
                        Action::make("disable_oauth_$id")
                            ->visible(fn (Get $get) => $get($key))
                            ->disabled(fn () => !user()?->can('update settings'))
                            ->label(trans('admin/setting.oauth.disable'))
                            ->color('danger')
                            ->action(fn (Set $set) => $set($key, false)),
                        Action::make("enable_oauth_$id")
                            ->visible(fn (Get $get) => !$get($key))
                            ->disabled(fn () => !user()?->can('update settings'))
                            ->label(trans('admin/setting.oauth.enable'))
                            ->color('success')
                            ->steps($schema->getSetupSteps())
                            ->modalHeading(trans('admin/setting.oauth.enable_schema', ['schema' => $schema->getName()]))
                            ->modalSubmitActionLabel(trans('admin/setting.oauth.enable'))
                            ->modalCancelAction(false)
                            ->action(function ($data, Set $set) use ($key) {
                                $data = array_merge([
                                    $key => 'true',
                                ], $data);

                                foreach ($data as $key => $value) {
                                    $set($key, $value);
                                }
                            }),
                    ])->columnSpan(1),
                    Group::make($schema->getSettingsForm())
                        ->visible(fn (Get $get) => $get($key))
                        ->columns(4)
                        ->columnSpan(4),
                ]);
        }

        return $formFields;
    }

    /**
     * @return Component[]
     *
     * @throws Exception
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
                        ->stateCast(new BooleanStateCast(false))
                        ->default(env('PANEL_CLIENT_ALLOCATIONS_ENABLED', config('panel.client_features.allocations.enabled'))),
                    Toggle::make('PANEL_CLIENT_ALLOCATIONS_CREATE_NEW')
                        ->label(trans('admin/setting.misc.auto_allocation.create_new'))
                        ->helperText(trans('admin/setting.misc.auto_allocation.create_new_help'))
                        ->onIcon('tabler-check')
                        ->offIcon('tabler-x')
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->columnSpanFull()
                        ->visible(fn (Get $get) => $get('PANEL_CLIENT_ALLOCATIONS_ENABLED'))
                        ->stateCast(new BooleanStateCast(false))
                        ->default(env('PANEL_CLIENT_ALLOCATIONS_CREATE_NEW', config('panel.client_features.allocations.create_new'))),
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
                        ->stateCast(new BooleanStateCast(false))
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
                        ->columnSpan(1)
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('PANEL_EDITABLE_SERVER_DESCRIPTIONS', (bool) $state))
                        ->default(env('PANEL_EDITABLE_SERVER_DESCRIPTIONS', config('panel.editable_server_descriptions'))),
                    FileUpload::make('ConsoleFonts')
                        ->hint(trans('admin/setting.misc.server.console_font_hint'))
                        ->label(trans('admin/setting.misc.server.console_font_upload'))
                        ->directory('fonts')
                        ->disk('public')
                        ->columnSpan(1)
                        ->maxFiles(1)
                        ->preserveFilenames(),
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
            unset($data['ConsoleFonts']);

            $data = array_map(function ($value) {
                // Convert bools to a string, so they are correctly written to the .env file
                if (is_bool($value)) {
                    return $value ? 'true' : 'false';
                }

                // Convert enum to its value
                if ($value instanceof BackedEnum) {
                    return $value->value;
                }

                return $value;
            }, $data);

            $this->writeToEnvironment($data);

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

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            Action::make('save')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-device-floppy')
                ->action('save')
                ->authorize(fn () => user()?->can('update settings'))
                ->keyBindings(['mod+s']),
        ];

    }
}
