<?php

namespace App\Filament\Admin\Pages;

use App\Enums\TablerIcon;
use App\Extensions\Avatar\AvatarService;
use App\Extensions\Captcha\CaptchaService;
use App\Extensions\OAuth\OAuthService;
use App\Models\Backup;
use App\Models\PwaPushSubscription;
use App\Notifications\MailTested;
use App\Services\Pwa\PwaActions;
use App\Services\Pwa\PwaPushService;
use App\Services\Pwa\PwaSettingsRepository;
use App\Traits\EnvironmentWriterTrait;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use App\Traits\Filament\CanCustomizeTabs;
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
    use CanCustomizeTabs;
    use EnvironmentWriterTrait;
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = TablerIcon::Settings;

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

    /** @return array<Component> */
    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Tabs')
                ->columns()
                ->persistTabInQueryString()
                ->disabled(fn () => !user()?->can('update settings'))
                ->tabs($this->getTabs()),
        ];
    }

    /**
     * @return Tab[]
     *
     * @throws Exception
     */
    protected function getDefaultTabs(): array
    {
        return [
            Tab::make('general')
                ->label(trans('admin/setting.navigation.general'))
                ->icon(TablerIcon::Home)
                ->schema($this->generalSettings()),
            Tab::make('captcha')
                ->label(trans('admin/setting.navigation.captcha'))
                ->icon(TablerIcon::Shield)
                ->schema($this->captchaSettings())
                ->columns(1),
            Tab::make('mail')
                ->label(trans('admin/setting.navigation.mail'))
                ->icon(TablerIcon::Mail)
                ->schema($this->mailSettings()),
            Tab::make('backup')
                ->label(trans('admin/setting.navigation.backup'))
                ->icon(TablerIcon::Box)
                ->schema($this->backupSettings()),
            Tab::make('oauth')
                ->label(trans('admin/setting.navigation.oauth'))
                ->icon(TablerIcon::BrandOauth)
                ->schema($this->oauthSettings())
                ->columns(1),
            Tab::make('misc')
                ->label(trans('admin/setting.navigation.misc'))
                ->icon(TablerIcon::Tool)
                ->schema($this->miscSettings()),
            Tab::make('pwa')
                ->label(trans('pwa.navigation.label'))
                ->icon('heroicon-o-device-phone-mobile')
                ->schema($this->pwaSettings()),
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
                        ->hintIcon(TablerIcon::QuestionMark, trans('admin/setting.general.app_logo_help'))
                        ->default(env('APP_LOGO'))
                        ->placeholder('/pelican.svg'),
                    TextInput::make('APP_FAVICON')
                        ->label(trans('admin/setting.general.app_favicon'))
                        ->hintIcon(TablerIcon::QuestionMark, trans('admin/setting.general.app_favicon_help'))
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
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
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
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
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
                    Action::make('hint_clear')
                        ->label(trans('admin/setting.general.clear'))
                        ->color('danger')
                        ->icon(TablerIcon::Trash)
                        ->requiresConfirmation()
                        ->authorize(fn () => user()?->can('update settings'))
                        ->action(fn (Set $set) => $set('TRUSTED_PROXIES', [])),
                    Action::make('hint_cloudflare')
                        ->label(trans('admin/setting.general.set_to_cf'))
                        ->icon(TablerIcon::BrandCloudflare)
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
                ->icon($schema->getIcon() ?? TablerIcon::Shield)
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
                    Action::make('hint_test')
                        ->label(trans('admin/setting.mail.test_mail'))
                        ->icon(TablerIcon::Send)
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
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
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
            $key = $schema->getConfigKey();

            $formFields[] = Section::make($schema->getName())
                ->columns(5)
                ->icon($schema->getIcon() ?? TablerIcon::BrandOauth)
                ->collapsed(fn () => !$schema->isEnabled())
                ->collapsible()
                ->schema([
                    Hidden::make($key)
                        ->live()
                        ->default($schema->isEnabled()),
                    Actions::make([
                        Action::make('disable_oauth_' . $schema->getId())
                            ->visible(fn (Get $get) => $get($key))
                            ->disabled(fn () => !user()?->can('update settings'))
                            ->label(trans('admin/setting.oauth.disable'))
                            ->color('danger')
                            ->action(fn (Set $set) => $set($key, false)),
                        Action::make('enable_oauth_'  . $schema->getId())
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
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->columnSpanFull()
                        ->stateCast(new BooleanStateCast(false))
                        ->default(env('PANEL_CLIENT_ALLOCATIONS_ENABLED', config('panel.client_features.allocations.enabled'))),
                    Toggle::make('PANEL_CLIENT_ALLOCATIONS_CREATE_NEW')
                        ->label(trans('admin/setting.misc.auto_allocation.create_new'))
                        ->helperText(trans('admin/setting.misc.auto_allocation.create_new_help'))
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
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
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('PANEL_SEND_INSTALL_NOTIFICATION', (bool) $state))
                        ->default(env('PANEL_SEND_INSTALL_NOTIFICATION', config('panel.email.send_install_notification'))),
                    Toggle::make('PANEL_SEND_REINSTALL_NOTIFICATION')
                        ->label(trans('admin/setting.misc.mail_notifications.server_reinstalled'))
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
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
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
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
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
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

    /**
     * @return Component[]
     */
    private function pwaSettings(): array
    {
        $pwa = app(PwaSettingsRepository::class); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
        $pwa->ensureVapidKeys();

        return [
            Section::make(trans('pwa.tabs.manifest'))
                ->description(trans('pwa.fields.theme_color.helper'))
                ->columns()
                ->collapsible()
                ->schema([
                    TextInput::make('pwa_theme_color')
                        ->label(trans('pwa.fields.theme_color.label'))
                        ->helperText(trans('pwa.fields.theme_color.helper'))
                        ->default($pwa->get('theme_color', '#0ea5e9')),
                    TextInput::make('pwa_background_color')
                        ->label(trans('pwa.fields.background_color.label'))
                        ->helperText(trans('pwa.fields.background_color.helper'))
                        ->default($pwa->get('background_color', '#0f172a')),
                    TextInput::make('pwa_start_url')
                        ->label(trans('pwa.fields.start_url.label'))
                        ->helperText(trans('pwa.fields.start_url.helper'))
                        ->default($pwa->get('start_url', '/')),
                    TextInput::make('pwa_cache_name')
                        ->label(trans('pwa.fields.cache_name.label'))
                        ->helperText(trans('pwa.fields.cache_name.helper'))
                        ->default($pwa->get('cache_name', 'pelican-pwa-v1')),
                    TextInput::make('pwa_cache_version')
                        ->label(trans('pwa.fields.cache_version.label'))
                        ->numeric()
                        ->default($pwa->get('cache_version', 1)),
                ]),
            Section::make(trans('pwa.fields.manifest_icon_192.label'))
                ->columns()
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextInput::make('pwa_manifest_icon_192')
                        ->label(trans('pwa.fields.manifest_icon_192.label'))
                        ->helperText(trans('pwa.fields.manifest_icon_192.helper'))
                        ->default($pwa->get('manifest_icon_192', '/pelican-192.png')),
                    TextInput::make('pwa_manifest_icon_512')
                        ->label(trans('pwa.fields.manifest_icon_512.label'))
                        ->helperText(trans('pwa.fields.manifest_icon_512.helper'))
                        ->default($pwa->get('manifest_icon_512', '/pelican-512.png')),
                    TextInput::make('pwa_apple_touch_icon')
                        ->label(trans('pwa.fields.apple_touch_icon.label'))
                        ->default($pwa->get('apple_touch_icon', '/pelican-180.png')),
                    TextInput::make('pwa_apple_touch_icon_152')
                        ->label(trans('pwa.fields.apple_touch_icon_152.label'))
                        ->default($pwa->get('apple_touch_icon_152', '/pelican-152.png')),
                    TextInput::make('pwa_apple_touch_icon_167')
                        ->label(trans('pwa.fields.apple_touch_icon_167.label'))
                        ->default($pwa->get('apple_touch_icon_167', '/pelican-167.png')),
                    TextInput::make('pwa_apple_touch_icon_180')
                        ->label(trans('pwa.fields.apple_touch_icon_180.label'))
                        ->default($pwa->get('apple_touch_icon_180', '/pelican-180.png')),
                ]),
            Section::make(trans('pwa.tabs.push'))
                ->columns()
                ->collapsible()
                ->collapsed()
                ->schema([
                    Toggle::make('pwa_push_enabled')
                        ->label(trans('pwa.fields.push_enabled.label'))
                        ->helperText(trans('pwa.fields.push_enabled.helper'))
                        ->inline(false)
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
                        ->onColor('success')
                        ->offColor('danger')
                        ->live()
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->default((bool) $pwa->get('push_enabled', false)),
                    Toggle::make('pwa_push_send_on_database_notifications')
                        ->label(trans('pwa.fields.push_send_on_db.label'))
                        ->helperText(trans('pwa.fields.push_send_on_db.helper'))
                        ->inline(false)
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
                        ->onColor('success')
                        ->offColor('danger')
                        ->visible(fn (Get $get) => $get('pwa_push_enabled'))
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->default((bool) $pwa->get('push_send_on_database_notifications', true)),
                    Toggle::make('pwa_push_send_on_mail_notifications')
                        ->label(trans('pwa.fields.push_send_on_mail.label'))
                        ->helperText(trans('pwa.fields.push_send_on_mail.helper'))
                        ->inline(false)
                        ->onIcon(TablerIcon::Check)
                        ->offIcon(TablerIcon::X)
                        ->onColor('success')
                        ->offColor('danger')
                        ->visible(fn (Get $get) => $get('pwa_push_enabled'))
                        ->formatStateUsing(fn ($state): bool => (bool) $state)
                        ->default((bool) $pwa->get('push_send_on_mail_notifications', false)),
                    TextInput::make('pwa_vapid_subject')
                        ->label(trans('pwa.fields.vapid_subject.label'))
                        ->helperText(trans('pwa.fields.vapid_subject.helper'))
                        ->visible(fn (Get $get) => $get('pwa_push_enabled'))
                        ->default($pwa->get('vapid_subject', '')),
                    TextInput::make('pwa_vapid_public_key')
                        ->label(trans('pwa.fields.vapid_public_key.label'))
                        ->visible(fn (Get $get) => $get('pwa_push_enabled'))
                        ->default($pwa->get('vapid_public_key', '')),
                    TextInput::make('pwa_vapid_private_key')
                        ->label(trans('pwa.fields.vapid_private_key.label'))
                        ->password()
                        ->revealable()
                        ->visible(fn (Get $get) => $get('pwa_push_enabled'))
                        ->default($pwa->get('vapid_private_key', '')),
                    TextInput::make('pwa_notification_icon')
                        ->label(trans('pwa.fields.default_notification_icon.label'))
                        ->helperText(trans('pwa.fields.default_notification_icon.helper'))
                        ->visible(fn (Get $get) => $get('pwa_push_enabled'))
                        ->default($pwa->get('default_notification_icon', '/pelican.svg')),
                    TextInput::make('pwa_notification_badge')
                        ->label(trans('pwa.fields.default_notification_badge.label'))
                        ->helperText(trans('pwa.fields.default_notification_badge.helper'))
                        ->visible(fn (Get $get) => $get('pwa_push_enabled'))
                        ->default($pwa->get('default_notification_badge', '/pelican.svg')),
                    Actions::make([
                        Action::make('pwa_test_push')
                            ->label(trans('pwa.actions.test_push'))
                            ->icon('heroicon-o-paper-airplane')
                            ->color('warning')
                            ->visible(fn (Get $get) => $get('pwa_push_enabled'))
                            ->requiresConfirmation()
                            ->action(function () {
                                $settings = app(PwaSettingsRepository::class); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
                                $push = app(PwaPushService::class); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
                                $user = user();

                                if (!$user) {
                                    Notification::make()->title(trans('pwa.errors.unauthorized'))->danger()->send();

                                    return;
                                }

                                if (!$push->canSend()) {
                                    Notification::make()->title(trans('pwa.errors.library_missing'))->danger()->send();

                                    return;
                                }

                                $vapid = [
                                    'subject' => $settings->get('vapid_subject', ''),
                                    'publicKey' => $settings->get('vapid_public_key', ''),
                                    'privateKey' => $settings->get('vapid_private_key', ''),
                                ];

                                if (!$vapid['publicKey'] || !$vapid['privateKey']) {
                                    Notification::make()->title(trans('pwa.errors.vapid_missing'))->danger()->send();

                                    return;
                                }

                                $subscription = PwaPushSubscription::query()
                                    ->where('notifiable_type', $user->getMorphClass())
                                    ->where('notifiable_id', $user->getKey())
                                    ->latest('id')
                                    ->first();

                                if (!$subscription) {
                                    Notification::make()->title(trans('pwa.errors.no_subscription'))->danger()->send();

                                    return;
                                }

                                $icon = asset(ltrim($settings->get('default_notification_icon', '/pelican.svg'), '/'));
                                $badge = asset(ltrim($settings->get('default_notification_badge', '/pelican.svg'), '/'));

                                $result = $push->sendToSubscription($subscription, [
                                    'title' => config('app.name', 'Pelican'),
                                    'body' => trans('pwa.messages.test_notification_body'),
                                    'icon' => $icon,
                                    'badge' => $badge,
                                    'url' => url('/'),
                                    'tag' => 'pwa-test',
                                ], $vapid);

                                if ($result === true) {
                                    Notification::make()->title(trans('pwa.notifications.test_sent'))->success()->send();
                                } else {
                                    Notification::make()->title(trans('pwa.errors.send_failed'))->body($result)->danger()->send();
                                }
                            }),
                    ]),
                ]),
            Section::make(trans('pwa.tabs.actions'))
                ->description(trans('pwa.profile.section_description'))
                ->collapsible()
                ->collapsed()
                ->schema([
                    PwaActions::make(),
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

            $pwaData = [];
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'pwa_')) {
                    $pwaData[substr($key, 4)] = $value;
                    unset($data[$key]);
                }
            }

            if ($pwaData !== []) {
                $pwaData = array_map(fn ($v) => is_bool($v) ? ($v ? 'true' : 'false') : $v, $pwaData);
                app(PwaSettingsRepository::class)->setMany($pwaData); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
            }

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
                ->hiddenLabel()
                ->icon(TablerIcon::DeviceFloppy)
                ->action('save')
                ->tooltip(trans('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->authorize(fn () => user()?->can('update settings'))
                ->keyBindings(['mod+s']),
        ];

    }
}
