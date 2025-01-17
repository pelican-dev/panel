<?php

namespace App\Filament\Pages\Auth;

use App\Exceptions\Service\User\TwoFactorAuthenticationTokenInvalid;
use App\Extensions\OAuth\Providers\OAuthProvider;
use App\Facades\Activity;
use App\Models\ActivityLog;
use App\Models\ApiKey;
use App\Models\User;
use App\Services\Users\UserUpdateService;
use DateTimeZone;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

/**
 * @method User getUser()
 */
class EditProfile extends BaseEditProfile
{
    public function getMaxWidth(): MaxWidth|string
    {
        return config('panel.filament.display-width', 'screen-2xl');
    }

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
                                            ->dehydrated(false)
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
                                            ->helperText(fn ($state, LanguageService $languageService) => new HtmlString($languageService->isLanguageTranslated($state) ? '' : "
                                                Your language ($state) has not been translated yet!
                                                But never fear, you can help fix that by
                                                <a style='color: rgb(56, 189, 248)' href='https://crowdin.com/project/pelican-dev'>contributing directly here</a>.
                                            "))
                                            ->options(fn (LanguageService $languageService) => $languageService->getAvailableLanguages()),
                                    ]),

                                Tab::make('OAuth')
                                    ->icon('tabler-brand-oauth')
                                    ->visible(function () {
                                        $oauthProviders = OAuthProvider::get();
                                        foreach ($oauthProviders as $oauthProvider) {
                                            if ($oauthProvider->isEnabled()) {
                                                return true;
                                            }
                                        }

                                        return false;
                                    })
                                    ->schema(function () {
                                        $actions = [];

                                        $oauthProviders = OAuthProvider::get();
                                        foreach ($oauthProviders as $oauthProvider) {
                                            if (!$oauthProvider->isEnabled()) {
                                                continue;
                                            }

                                            $id = $oauthProvider->getId();
                                            $name = $oauthProvider->getName();

                                            $unlink = array_key_exists($id, $this->getUser()->oauth ?? []);

                                            $actions[] = Action::make("oauth_$id")
                                                ->label(($unlink ? 'Unlink ' : 'Link ') . $name)
                                                ->icon($unlink ? 'tabler-unlink' : 'tabler-link')
                                                ->color(Color::hex($oauthProvider->getHexColor()))
                                                ->action(function (UserUpdateService $updateService) use ($id, $name, $unlink) {
                                                    if ($unlink) {
                                                        $oauth = auth()->user()->oauth;
                                                        unset($oauth[$id]);

                                                        $updateService->handle(auth()->user(), ['oauth' => $oauth]);

                                                        $this->fillForm();

                                                        Notification::make()
                                                            ->title("OAuth provider '$name' unlinked")
                                                            ->success()
                                                            ->send();
                                                    } else {
                                                        redirect(Socialite::with($name)->redirect()->getTargetUrl());
                                                    }
                                                });
                                        }

                                        return [Actions::make($actions)];
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
                                                    ->successRedirectUrl(self::getUrl(['tab' => '-api-keys-tab']))
                                                    ->action(function (Get $get, Action $action, User $user) {
                                                        $token = $user->createToken(
                                                            $get('description'),
                                                            $get('allowed_ips'),
                                                        );

                                                        Activity::event('user:api-key.create')
                                                            ->subject($token->accessToken)
                                                            ->property('identifier', $token->accessToken->identifier)
                                                            ->log();

                                                        Notification::make()
                                                            ->title('API Key created')
                                                            ->body($token->accessToken->identifier . $token->plainTextToken)
                                                            ->persistent()
                                                            ->success()
                                                            ->send();

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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$record instanceof User) {
            return $record;
        }

        if ($token = $data['2facode'] ?? null) {
            $tokens = $this->toggleTwoFactorService->handle($record, $token, true);
            cache()->put("users.$record->id.2fa.tokens", implode("\n", $tokens), now()->addSeconds(15));

            $this->redirectRoute('filament.admin.auth.profile', ['tab' => '-2fa-tab']);
        }

        if ($token = $data['2fa-disable-code'] ?? null) {
            try {
                $this->toggleTwoFactorService->handle($record, $token, false);
            } catch (TwoFactorAuthenticationTokenInvalid $exception) {
                Notification::make()
                    ->title('Invalid 2FA Code')
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
}
