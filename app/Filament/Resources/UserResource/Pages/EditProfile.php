<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Exceptions\Service\User\TwoFactorAuthenticationTokenInvalid;
use App\Facades\Activity;
use App\Models\ActivityLog;
use App\Models\ApiKey;
use App\Models\User;
use App\Services\Users\ToggleTwoFactorService;
use App\Services\Users\TwoFactorSetupService;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Common\Version;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use DateTimeZone;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;

/**
 * @method User getUser()
 */
class EditProfile extends \Filament\Pages\Auth\EditProfile
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Tabs::make()->persistTabInQueryString()
                            ->schema([
                                Tab::make('Account')
                                    ->label(trans('strings.account'))
                                    ->icon('tabler-user')
                                    ->schema([
                                        TextInput::make('username')
                                            ->label(trans('strings.username'))
                                            ->disabled()
                                            ->readOnly()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->autofocus(),

                                        TextInput::make('email')
                                            ->prefixIcon('tabler-mail')
                                            ->label(trans('strings.email'))
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true),

                                        TextInput::make('password')
                                            ->label(trans('strings.password'))
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
                                            ->label(trans('strings.password_confirmation'))
                                            ->password()
                                            ->prefixIcon('tabler-password-fingerprint')
                                            ->revealable(filament()->arePasswordsRevealable())
                                            ->required()
                                            ->visible(fn (Get $get): bool => filled($get('password')))
                                            ->dehydrated(false),

                                        Select::make('timezone')
                                            ->required()
                                            ->prefixIcon('tabler-clock-pin')
                                            ->options(fn () => collect(DateTimeZone::listIdentifiers())->mapWithKeys(fn ($tz) => [$tz => $tz]))
                                            ->searchable(),

                                        Select::make('language')
                                            ->label(trans('strings.language'))
                                            ->required()
                                            ->prefixIcon('tabler-flag')
                                            ->live()
                                            ->default('en')
                                            ->helperText(fn (User $user, $state) => new HtmlString($user->isLanguageTranslated($state) ? '' : "
                                                Your language ($state) has not been translated yet!
                                                But never fear, you can help fix that by
                                                <a style='color: rgb(56, 189, 248)' href='https://crowdin.com/project/pelican-dev'>contributing directly here</a>.
                                            ")
                                            )
                                            ->options(fn (User $user) => $user->getAvailableLanguages()),
                                    ]),

                                Tab::make('2FA')
                                    ->icon('tabler-shield-lock')
                                    ->schema(function () {

                                        if ($this->getUser()->use_totp) {
                                            return [
                                                Placeholder::make('2fa-already-enabled')
                                                    ->label('Two Factor Authentication is currently enabled!'),
                                                Textarea::make('backup-tokens')
                                                    ->hidden(fn () => !cache()->get("users.{$this->getUser()->id}.2fa.tokens"))
                                                    ->rows(10)
                                                    ->readOnly()
                                                    ->formatStateUsing(fn () => cache()->get("users.{$this->getUser()->id}.2fa.tokens"))
                                                    ->helperText('These will not be shown again!')
                                                    ->label('Backup Tokens:'),
                                                TextInput::make('2fa-disable-code')
                                                    ->label('Disable 2FA')
                                                    ->helperText('Enter your current 2FA code to disable Two Factor Authentication'),
                                            ];
                                        }
                                        /** @var TwoFactorSetupService */
                                        $setupService = app(TwoFactorSetupService::class);

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
                                        ]);

                                        // https://github.com/chillerlan/php-qrcode/blob/main/examples/svgWithLogo.php

                                        // QROptions
                                        // @phpstan-ignore property.protected
                                        $options->version = Version::AUTO;
                                        // $options->outputInterface     = QRSvgWithLogo::class;
                                        // @phpstan-ignore property.protected
                                        $options->outputBase64 = false;
                                        // @phpstan-ignore property.protected
                                        $options->eccLevel = EccLevel::H; // ECC level H is necessary when using logos
                                        // @phpstan-ignore property.protected
                                        $options->addQuietzone = true;
                                        // $options->drawLightModules    = true;
                                        // @phpstan-ignore property.protected
                                        $options->connectPaths = true;
                                        // @phpstan-ignore property.protected
                                        $options->drawCircularModules = true;
                                        // $options->circleRadius        = 0.45;

                                        // @phpstan-ignore property.protected
                                        $options->svgDefs = '<linearGradient id="gradient" x1="100%" y2="100%">
                                            <stop stop-color="#7dd4fc" offset="0"/>
                                            <stop stop-color="#38bdf8" offset="0.5"/>
                                            <stop stop-color="#0369a1" offset="1"/>
                                        </linearGradient>
                                        <style><![CDATA[
                                            .dark{fill: url(#gradient);}
                                            .light{fill: #000;}
                                        ]]></style>';

                                        $image = (new QRCode($options))->render($url);

                                        return [
                                            Placeholder::make('qr')
                                                ->label('Scan QR Code')
                                                ->content(fn () => new HtmlString("
                                                <div style='width: 300px; background-color: rgb(24, 24, 27);'>$image</div>
                                            "))
                                                ->helperText('Setup Key: '. $secret),
                                            TextInput::make('2facode')
                                                ->label('Code')
                                                ->requiredWith('2fapassword')
                                                ->helperText('Scan the QR code above using your two-step authentication app, then enter the code generated.'),
                                            TextInput::make('2fapassword')
                                                ->label('Current Password')
                                                ->requiredWith('2facode')
                                                ->currentPassword()
                                                ->password()
                                                ->helperText('Enter your current password to verify.'),
                                        ];
                                    }),

                                Tab::make('API Keys')
                                    ->icon('tabler-key')
                                    ->schema([
                                        Grid::make('asdf')->columns(5)->schema([
                                            Section::make('Create API Key')->columnSpan(3)->schema([

                                                TextInput::make('description')
                                                    ->live(),

                                                TagsInput::make('allowed_ips')
                                                    ->live()
                                                    ->splitKeys([',', ' ', 'Tab'])
                                                    ->placeholder('Example: 127.0.0.1 or 192.168.1.1')
                                                    ->label('Whitelisted IP\'s')
                                                    ->helperText('Press enter to add a new IP address or leave blank to allow any IP address')
                                                    ->columnSpanFull(),
                                            ])->headerActions([
                                                Action::make('Create')
                                                    ->disabled(fn (Get $get) => $get('description') === null)
                                                    ->successRedirectUrl(route('filament.admin.auth.profile', ['tab' => '-api-keys-tab']))
                                                    ->action(function (Get $get, Action $action, User $user) {
                                                        $token = $user->createToken(
                                                            $get('description'),
                                                            $get('allowed_ips'),
                                                        );

                                                        Activity::event('user:api-key.create')
                                                            ->subject($token->accessToken)
                                                            ->property('identifier', $token->accessToken->identifier)
                                                            ->log();

                                                        $action->success();
                                                    }),
                                            ]),
                                            Section::make('Keys')->columnSpan(2)->schema([
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

                                Tab::make('SSH Keys')
                                    ->icon('tabler-lock-code')
                                    ->schema([
                                        Placeholder::make('Coming soon!'),
                                    ]),

                                Tab::make('Activity')
                                    ->icon('tabler-history')
                                    ->schema([
                                        Repeater::make('activity')
                                            ->deletable(false)
                                            ->addable(false)
                                            ->relationship(null, function (Builder $query) {
                                                $query->orderBy('timestamp', 'desc');
                                            })
                                            ->schema([
                                                Placeholder::make('activity!')->label('')->content(fn (ActivityLog $log) => new HtmlString($log->htmlable())),
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

    protected function handleRecordUpdate($record, $data): \Illuminate\Database\Eloquent\Model
    {
        if ($token = $data['2facode'] ?? null) {
            /** @var ToggleTwoFactorService $service */
            $service = resolve(ToggleTwoFactorService::class);

            $tokens = $service->handle($record, $token, true);
            cache()->set("users.$record->id.2fa.tokens", implode("\n", $tokens), now()->addSeconds(15));

            $this->redirectRoute('filament.admin.auth.profile', ['tab' => '-2fa-tab']);
        }

        if ($token = $data['2fa-disable-code'] ?? null) {
            /** @var ToggleTwoFactorService $service */
            $service = resolve(ToggleTwoFactorService::class);

            $service->handle($record, $token, false);

            cache()->forget("users.$record->id.2fa.state");
        }

        return parent::handleRecordUpdate($record, $data);
    }

    public function exception($e, $stopPropagation): void
    {
        if ($e instanceof TwoFactorAuthenticationTokenInvalid) {
            Notification::make()
                ->title('Invalid 2FA Code')
                ->body($e->getMessage())
                ->color('danger')
                ->icon('tabler-2fa')
                ->danger()
                ->send();

            $stopPropagation();
        }
    }
}
