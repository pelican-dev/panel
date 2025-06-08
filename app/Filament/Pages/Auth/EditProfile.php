<?php

namespace App\Filament\Pages\Auth;

use App\Exceptions\Service\User\TwoFactorAuthenticationTokenInvalid;
use App\Extensions\OAuth\Providers\OAuthProvider;
use App\Facades\Activity;
use App\Models\ActivityLog;
use App\Models\ApiKey;
use App\Models\User;
use App\Services\Helpers\LanguageService;
use App\Services\Users\ToggleTwoFactorService;
use App\Services\Users\TwoFactorSetupService;
use App\Services\Users\UserUpdateService;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Common\Version;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use DateTimeZone;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

/**
 * @method User getUser()
 */
class EditProfile extends BaseEditProfile
{
    private ToggleTwoFactorService $toggleTwoFactorService;

    public function boot(ToggleTwoFactorService $toggleTwoFactorService): void
    {
        $this->toggleTwoFactorService = $toggleTwoFactorService;
    }

    public function getMaxWidth(): MaxWidth|string
    {
        return config('panel.filament.display-width', 'screen-2xl');
    }

    protected function getForms(): array
    {
        $oauthProviders = collect(OAuthProvider::get())->filter(fn (OAuthProvider $provider) => $provider->isEnabled())->all();

        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Tabs::make()->persistTabInQueryString()
                            ->schema([
                                Tab::make(trans('profile.tabs.account'))
                                    ->icon('tabler-user')
                                    ->schema([
                                        TextInput::make('username')
                                            ->label(trans('profile.username'))
                                            ->disabled()
                                            ->readOnly()
                                            ->dehydrated(false)
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->autofocus(),
                                        TextInput::make('email')
                                            ->prefixIcon('tabler-mail')
                                            ->label(trans('profile.email'))
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true),
                                        TextInput::make('password')
                                            ->label(trans('profile.password'))
                                            ->password()
                                            ->prefixIcon('tabler-password')
                                            ->revealable(filament()->arePasswordsRevealable())
                                            ->rule(Password::default())
                                            ->autocomplete('new-password')
                                            ->dehydrated(fn ($state): bool => filled($state))
                                            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                                            ->live(debounce: 500)
                                            ->same('passwordConfirmation'),
                                        TextInput::make('passwordConfirmation')
                                            ->label(trans('profile.password_confirmation'))
                                            ->password()
                                            ->prefixIcon('tabler-password-fingerprint')
                                            ->revealable(filament()->arePasswordsRevealable())
                                            ->required()
                                            ->visible(fn (Get $get): bool => filled($get('password')))
                                            ->dehydrated(false),
                                        Select::make('timezone')
                                            ->label(trans('profile.timezone'))
                                            ->required()
                                            ->prefixIcon('tabler-clock-pin')
                                            ->default('UTC')
                                            ->selectablePlaceholder(false)
                                            ->options(fn () => collect(DateTimeZone::listIdentifiers())->mapWithKeys(fn ($tz) => [$tz => $tz]))
                                            ->searchable()
                                            ->native(false),
                                        Select::make('language')
                                            ->label(trans('profile.language'))
                                            ->required()
                                            ->prefixIcon('tabler-flag')
                                            ->live()
                                            ->default('en')
                                            ->selectablePlaceholder(false)
                                            ->helperText(fn ($state, LanguageService $languageService) => new HtmlString($languageService->isLanguageTranslated($state) ? '' : trans('profile.language_help', ['state' => $state])))
                                            ->options(fn (LanguageService $languageService) => $languageService->getAvailableLanguages())
                                            ->native(false),
                                        FileUpload::make('avatar')
                                            ->visible(fn () => config('panel.filament.uploadable-avatars'))
                                            ->avatar()
                                            ->acceptedFileTypes(['image/png'])
                                            ->directory('avatars')
                                            ->getUploadedFileNameForStorageUsing(fn () => $this->getUser()->id . '.png')
                                            ->hintAction(function (FileUpload $fileUpload) {
                                                $path = $fileUpload->getDirectory() . '/' . $this->getUser()->id . '.png';

                                                return Action::make('remove_avatar')
                                                    ->icon('tabler-photo-minus')
                                                    ->iconButton()
                                                    ->hidden(fn () => !$fileUpload->getDisk()->exists($path))
                                                    ->action(fn () => $fileUpload->getDisk()->delete($path));
                                            }),
                                    ]),

                                Tab::make(trans('profile.tabs.oauth'))
                                    ->icon('tabler-brand-oauth')
                                    ->visible(count($oauthProviders) > 0)
                                    ->schema(function () use ($oauthProviders) {
                                        $actions = [];

                                        foreach ($oauthProviders as $oauthProvider) {

                                            $id = $oauthProvider->getId();
                                            $name = $oauthProvider->getName();

                                            $unlink = array_key_exists($id, $this->getUser()->oauth ?? []);

                                            $actions[] = Action::make("oauth_$id")
                                                ->label(($unlink ? trans('profile.unlink') : trans('profile.link')) . $name)
                                                ->icon($unlink ? 'tabler-unlink' : 'tabler-link')
                                                ->color(Color::hex($oauthProvider->getHexColor()))
                                                ->action(function (UserUpdateService $updateService) use ($id, $name, $unlink) {
                                                    if ($unlink) {
                                                        $oauth = auth()->user()->oauth;
                                                        unset($oauth[$id]);

                                                        $updateService->handle(auth()->user(), ['oauth' => $oauth]);

                                                        $this->fillForm();

                                                        Notification::make()
                                                            ->title(trans('profile.unlinked', ['name' => $name]))
                                                            ->success()
                                                            ->send();
                                                    } else {
                                                        redirect(Socialite::with($id)->redirect()->getTargetUrl());
                                                    }
                                                });
                                        }

                                        return [Actions::make($actions)];
                                    }),

                                Tab::make(trans('profile.tabs.2fa'))
                                    ->icon('tabler-shield-lock')
                                    ->schema(function (TwoFactorSetupService $setupService) {
                                        if ($this->getUser()->use_totp) {
                                            return [
                                                Placeholder::make('2fa-already-enabled')
                                                    ->label(trans('profile.2fa_enabled')),
                                                Textarea::make('backup-tokens')
                                                    ->hidden(fn () => !cache()->get("users.{$this->getUser()->id}.2fa.tokens"))
                                                    ->rows(10)
                                                    ->readOnly()
                                                    ->dehydrated(false)
                                                    ->formatStateUsing(fn () => cache()->get("users.{$this->getUser()->id}.2fa.tokens"))
                                                    ->helperText(trans('profile.backup_help'))
                                                    ->label(trans('profile.backup_codes')),
                                                TextInput::make('2fa-disable-code')
                                                    ->label(trans('profile.disable_2fa'))
                                                    ->helperText(trans('profile.disable_2fa_help')),
                                            ];
                                        }

                                        ['image_url_data' => $url, 'secret' => $secret] = cache()->remember(
                                            "users.{$this->getUser()->id}.2fa.state",
                                            now()->addMinutes(5), fn () => $setupService->handle($this->getUser())
                                        );

                                        $options = new QROptions([
                                            'svgLogo' => public_path('pelican.svg'),
                                            'svgLogoScale' => 0.05,
                                            'addLogoSpace' => true,
                                            'logoSpaceWidth' => 13,
                                            'logoSpaceHeight' => 13,
                                            'version' => Version::AUTO,
                                            // 'outputInterface' => QRSvgWithLogo::class,
                                            'outputBase64' => false,
                                            'eccLevel' => EccLevel::H, // ECC level H is necessary when using logos
                                            'addQuietzone' => true,
                                            // 'drawLightModules' => true,
                                            'connectPaths' => true,
                                            'drawCircularModules' => true,
                                            // 'circleRadius' => 0.45,
                                            'svgDefs' => '
                                                <linearGradient id="gradient" x1="100%" y2="100%">
                                                    <stop stop-color="#7dd4fc" offset="0"/>
                                                    <stop stop-color="#38bdf8" offset="0.5"/>
                                                    <stop stop-color="#0369a1" offset="1"/>
                                                </linearGradient>
                                                <style><![CDATA[
                                                    .dark{fill: url(#gradient);}
                                                    .light{fill: #000;}
                                                ]]></style>
                                            ',
                                        ]);

                                        // https://github.com/chillerlan/php-qrcode/blob/main/examples/svgWithLogo.php

                                        $image = (new QRCode($options))->render($url);

                                        return [
                                            Placeholder::make('qr')
                                                ->label(trans('profile.scan_qr'))
                                                ->content(fn () => new HtmlString("
                                                <div style='width: 300px; background-color: rgb(24, 24, 27);'>$image</div>
                                            "))
                                                ->helperText(trans('profile.setup_key') .': '. $secret),
                                            TextInput::make('2facode')
                                                ->label(trans('profile.code'))
                                                ->requiredWith('2fapassword')
                                                ->helperText(trans('profile.code_help')),
                                            TextInput::make('2fapassword')
                                                ->label(trans('profile.current_password'))
                                                ->requiredWith('2facode')
                                                ->currentPassword()
                                                ->password(),
                                        ];
                                    }),

                                Tab::make(trans('profile.tabs.api_keys'))
                                    ->icon('tabler-key')
                                    ->schema([
                                        Grid::make('name')->columns(5)->schema([
                                            Section::make(trans('profile.create_key'))->columnSpan(3)->schema([
                                                TextInput::make('description')
                                                    ->label(trans('profile.description'))
                                                    ->live(),
                                                TagsInput::make('allowed_ips')
                                                    ->label(trans('profile.allowed_ips'))
                                                    ->live()
                                                    ->splitKeys([',', ' ', 'Tab'])
                                                    ->placeholder('127.0.0.1 or 192.168.1.1')
                                                    ->helperText(trans('profile.allowed_ips_help'))
                                                    ->columnSpanFull(),
                                            ])->headerActions([
                                                Action::make('Create')
                                                    ->label(trans('filament-actions::create.single.modal.actions.create.label'))
                                                    ->disabled(fn (Get $get) => $get('description') === null)
                                                    ->successRedirectUrl(self::getUrl(['tab' => '-api-keys-tab'], panel: 'app'))
                                                    ->action(function (Get $get, Action $action, User $user) {
                                                        $token = $user->createToken(
                                                            $get('description'),
                                                            $get('allowed_ips'),
                                                        );

                                                        Activity::event('user:api-key.create')
                                                            ->actor($user)
                                                            ->subject($user)
                                                            ->subject($token->accessToken)
                                                            ->property('identifier', $token->accessToken->identifier)
                                                            ->log();

                                                        Notification::make()
                                                            ->title(trans('profile.key_created'))
                                                            ->body($token->accessToken->identifier . $token->plainTextToken)
                                                            ->persistent()
                                                            ->success()
                                                            ->send();

                                                        $action->success();
                                                    }),
                                            ]),
                                            Section::make(trans('profile.keys'))->label(trans('profile.keys'))->columnSpan(2)->schema([
                                                Repeater::make('keys')
                                                    ->label('')
                                                    ->relationship('apiKeys')
                                                    ->addable(false)
                                                    ->itemLabel(fn ($state) => $state['identifier'])
                                                    ->deleteAction(function (Action $action) {
                                                        $action->requiresConfirmation()->action(function (array $arguments, Repeater $component) {
                                                            $items = $component->getState();
                                                            $key = $items[$arguments['item']];
                                                            ApiKey::find($key['id'] ?? null)?->delete();

                                                            unset($items[$arguments['item']]);

                                                            $component->state($items);

                                                            $component->callAfterStateUpdated();
                                                        });
                                                    })
                                                    ->schema(fn () => [
                                                        Placeholder::make('adf')->label(fn (ApiKey $key) => $key->memo),
                                                    ]),
                                            ]),
                                        ]),
                                    ]),

                                Tab::make(trans('profile.tabs.ssh_keys'))
                                    ->icon('tabler-lock-code')
                                    ->hidden(),

                                Tab::make(trans('profile.tabs.activity'))
                                    ->icon('tabler-history')
                                    ->schema([
                                        Repeater::make('activity')
                                            ->label('')
                                            ->deletable(false)
                                            ->addable(false)
                                            ->relationship(null, function (Builder $query) {
                                                $query->orderBy('timestamp', 'desc');
                                            })
                                            ->schema([
                                                Placeholder::make('activity!')->label('')->content(fn (ActivityLog $log) => new HtmlString($log->htmlable())),
                                            ]),
                                    ]),

                                Tab::make(trans('profile.tabs.customization'))
                                    ->icon('tabler-adjustments')
                                    ->schema([
                                        Section::make(trans('profile.dashboard'))
                                            ->collapsible()
                                            ->icon('tabler-dashboard')
                                            ->schema([
                                                ToggleButtons::make('dashboard_layout')
                                                    ->label(trans('profile.dashboard_layout'))
                                                    ->inline()
                                                    ->required()
                                                    ->options([
                                                        'grid' => trans('profile.grid'),
                                                        'table' => trans('profile.table'),
                                                    ]),
                                            ]),
                                        Section::make(trans('profile.console'))
                                            ->collapsible()
                                            ->icon('tabler-brand-tabler')
                                            ->columns(4)
                                            ->schema([
                                                TextInput::make('console_font_size')
                                                    ->label(trans('profile.font_size'))
                                                    ->columnSpan(1)
                                                    ->minValue(1)
                                                    ->numeric()
                                                    ->required()
                                                    ->default(14),
                                                Select::make('console_font')
                                                    ->label(trans('profile.font'))
                                                    ->required()
                                                    ->options(function () {
                                                        $fonts = [
                                                            'monospace' => 'monospace', //default
                                                        ];

                                                        if (!Storage::disk('public')->exists('fonts')) {
                                                            Storage::disk('public')->makeDirectory('fonts');
                                                            $this->fillForm();
                                                        }

                                                        foreach (Storage::disk('public')->allFiles('fonts') as $file) {
                                                            $fileInfo = pathinfo($file);

                                                            if ($fileInfo['extension'] === 'ttf') {
                                                                $fonts[$fileInfo['filename']] = $fileInfo['filename'];
                                                            }
                                                        }

                                                        return $fonts;
                                                    })
                                                    ->reactive()
                                                    ->default('monospace')
                                                    ->afterStateUpdated(fn ($state, Set $set) => $set('font_preview', $state)),
                                                Placeholder::make('font_preview')
                                                    ->label(trans('profile.font_preview'))
                                                    ->columnSpan(2)
                                                    ->content(function (Get $get) {
                                                        $fontName = $get('console_font') ?? 'monospace';
                                                        $fontSize = $get('console_font_size') . 'px';
                                                        $style = <<<CSS
                                                            .preview-text {
                                                                font-family: $fontName;
                                                                font-size: $fontSize;
                                                                margin-top: 10px;
                                                                display: block;
                                                            }
                                                        CSS;
                                                        if ($fontName !== 'monospace') {
                                                            $fontUrl = asset("storage/fonts/$fontName.ttf");
                                                            $style = <<<CSS
                                                                @font-face {
                                                                    font-family: $fontName;
                                                                    src: url("$fontUrl");
                                                                }
                                                                $style
                                                            CSS;
                                                        }

                                                        return new HtmlString(<<<HTML
                                                            <style>
                                                            {$style}  
                                                            </style>
                                                            <span class="preview-text">The quick blue pelican jumps over the lazy pterodactyl. :)</span>
                                                        HTML);
                                                    }),
                                                TextInput::make('console_graph_period')
                                                    ->label(trans('profile.graph_period'))
                                                    ->suffix(trans('profile.seconds'))
                                                    ->hintIcon('tabler-question-mark')
                                                    ->hintIconTooltip(trans('profile.graph_period_helper'))
                                                    ->columnSpan(2)
                                                    ->numeric()
                                                    ->default(30)
                                                    ->minValue(10)
                                                    ->maxValue(120)
                                                    ->required(),
                                                TextInput::make('console_rows')
                                                    ->label(trans('profile.rows'))
                                                    ->minValue(1)
                                                    ->numeric()
                                                    ->required()
                                                    ->columnSpan(2)
                                                    ->default(30),
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(!static::isSimple()),
            ),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$record instanceof User) {
            return $record;
        }

        if ($token = $data['2facode'] ?? null) {
            $tokens = $this->toggleTwoFactorService->handle($record, $token, true);
            cache()->put("users.$record->id.2fa.tokens", implode("\n", $tokens), now()->addSeconds(15));

            $this->redirect(self::getUrl(['tab' => '-2fa-tab'], panel: 'app'));
        }

        if ($token = $data['2fa-disable-code'] ?? null) {
            try {
                $this->toggleTwoFactorService->handle($record, $token, false);
            } catch (TwoFactorAuthenticationTokenInvalid $exception) {
                Notification::make()
                    ->title(trans('profile.invalid_code'))
                    ->body($exception->getMessage())
                    ->color('danger')
                    ->icon('tabler-2fa')
                    ->danger()
                    ->send();

                throw new Halt();
            }

            cache()->forget("users.$record->id.2fa.state");
        }

        return parent::handleRecordUpdate($record, $data);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveFormAction()->formId('form'),
        ];

    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $moarbetterdata = [
            'console_font' => $data['console_font'],
            'console_font_size' => $data['console_font_size'],
            'console_rows' => $data['console_rows'],
            'console_graph_period' => $data['console_graph_period'],
            'dashboard_layout' => $data['dashboard_layout'],
        ];

        unset($data['console_font'],$data['console_font_size'], $data['console_rows'], $data['dashboard_layout']);
        $data['customization'] = json_encode($moarbetterdata);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $moarbetterdata = json_decode($data['customization'], true);

        $data['console_font'] = $moarbetterdata['console_font'] ?? 'monospace';
        $data['console_font_size'] = $moarbetterdata['console_font_size'] ?? 14;
        $data['console_rows'] = $moarbetterdata['console_rows'] ?? 30;
        $data['console_graph_period'] = $moarbetterdata['console_graph_period'] ?? 30;
        $data['dashboard_layout'] = $moarbetterdata['dashboard_layout'] ?? 'grid';

        return $data;
    }
}
