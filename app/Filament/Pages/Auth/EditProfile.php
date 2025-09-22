<?php

namespace App\Filament\Pages\Auth;

use App\Enums\CustomizationKey;
use App\Extensions\OAuth\OAuthService;
use App\Facades\Activity;
use App\Models\ActivityLog;
use App\Models\ApiKey;
use App\Models\User;
use App\Models\UserSSHKey;
use App\Services\Helpers\LanguageService;
use App\Services\Ssh\KeyCreationService;
use App\Services\Users\UserUpdateService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use DateTimeZone;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Auth\MultiFactor\Contracts\MultiFactorAuthenticationProvider;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Builder;
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
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected OAuthService $oauthService;

    public function boot(OAuthService $oauthService): void
    {
        $this->oauthService = $oauthService;
    }

    public function getMaxWidth(): Width|string
    {
        return config('panel.filament.display-width', 'screen-2xl');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    /**
     * @throws Exception
     */
    public function form(Schema $schema): Schema
    {
        $oauthSchemas = $this->oauthService->getEnabled();

        return $schema
            ->components([
                Tabs::make()->persistTabInQueryString()
                    ->schema([
                        Tab::make('account')
                            ->label(trans('profile.tabs.account'))
                            ->icon('tabler-user-cog')
                            ->schema([
                                TextInput::make('username')
                                    ->prefixIcon('tabler-user')
                                    ->label(trans('profile.username'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(),
                                TextInput::make('email')
                                    ->prefixIcon('tabler-mail')
                                    ->label(trans('profile.email'))
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(),
                                TextInput::make('password')
                                    ->label(trans('profile.password'))
                                    ->password()
                                    ->prefixIcon('tabler-password')
                                    ->revealable(filament()->arePasswordsRevealable())
                                    ->rule(Password::default())
                                    ->autocomplete('new-password')
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->live(debounce: 500)
                                    ->same('passwordConfirmation'),
                                TextInput::make('passwordConfirmation')
                                    ->label(trans('profile.password_confirmation'))
                                    ->password()
                                    ->prefixIcon('tabler-password-fingerprint')
                                    ->revealable(filament()->arePasswordsRevealable())
                                    ->required()
                                    ->visible(fn (Get $get) => filled($get('password')))
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
                                    ->helperText(fn ($state, LanguageService $languageService) => new HtmlString($languageService->isLanguageTranslated($state) ? ''
                                            : trans('profile.language_help', ['state' => $state]) . ' <u><a href="https://crowdin.com/project/pelican-dev/">Update On Crowdin</a></u>'))
                                    ->options(fn (LanguageService $languageService) => $languageService->getAvailableLanguages())
                                    ->native(false),
                                FileUpload::make('avatar')
                                    ->visible(fn () => config('panel.filament.uploadable-avatars'))
                                    ->avatar()
                                    ->acceptedFileTypes(['image/png'])
                                    ->directory('avatars')
                                    ->disk('public')
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
                        Tab::make('oauth')
                            ->label(trans('profile.tabs.oauth'))
                            ->icon('tabler-brand-oauth')
                            ->visible(count($oauthSchemas) > 0)
                            ->schema(function () use ($oauthSchemas) {
                                $actions = [];

                                foreach ($oauthSchemas as $schema) {

                                    $id = $schema->getId();
                                    $name = $schema->getName();

                                    $unlink = array_key_exists($id, $this->getUser()->oauth ?? []);

                                    $actions[] = Action::make("oauth_$id")
                                        ->label(trans('profile.' . ($unlink ? 'unlink' : 'link'), ['name' => $name]))
                                        ->icon($unlink ? 'tabler-unlink' : 'tabler-link')
                                        ->color(Color::hex($schema->getHexColor()))
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
                        Tab::make('2fa')
                            ->label(trans('profile.tabs.2fa'))
                            ->icon('tabler-shield-lock')
                            ->visible(fn () => Filament::hasMultiFactorAuthentication())
                            ->schema(collect(Filament::getMultiFactorAuthenticationProviders())
                                ->sort(fn (MultiFactorAuthenticationProvider $multiFactorAuthenticationProvider) => $multiFactorAuthenticationProvider->isEnabled(Filament::auth()->user()) ? 0 : 1)
                                ->map(fn (MultiFactorAuthenticationProvider $multiFactorAuthenticationProvider) => Group::make($multiFactorAuthenticationProvider->getManagementSchemaComponents())
                                    ->statePath($multiFactorAuthenticationProvider->getId()))
                                ->all()),
                        Tab::make('api_keys')
                            ->label(trans('profile.tabs.api_keys'))
                            ->icon('tabler-key')
                            ->schema([
                                Grid::make(5)
                                    ->schema([
                                        Section::make(trans('profile.create_api_key'))->columnSpan(3)
                                            ->schema([
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
                                            ])
                                            ->headerActions([
                                                Action::make('create')
                                                    ->label(trans('filament-actions::create.single.modal.actions.create.label'))
                                                    ->disabled(fn (Get $get) => empty($get('description')))
                                                    ->successRedirectUrl(self::getUrl(['tab' => 'api-keys::data::tab'], panel: 'app'))
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
                                                            ->title(trans('profile.api_key_created'))
                                                            ->body($token->accessToken->identifier . $token->plainTextToken)
                                                            ->persistent()
                                                            ->success()
                                                            ->send();

                                                        $action->success();
                                                    }),
                                            ]),
                                        Section::make(trans('profile.api_keys'))->columnSpan(2)
                                            ->schema([
                                                Repeater::make('api_keys')
                                                    ->hiddenLabel()
                                                    ->inlineLabel(false)
                                                    ->relationship('apiKeys')
                                                    ->addable(false)
                                                    ->itemLabel(fn ($state) => $state['identifier'])
                                                    ->deleteAction(function (Action $action) {
                                                        $action->requiresConfirmation()->action(function (array $arguments, Repeater $component, User $user) {
                                                            $items = $component->getState();
                                                            $key = $items[$arguments['item']];

                                                            $apiKey = ApiKey::find($key['id'] ?? null);
                                                            if ($apiKey->exists()) {
                                                                $apiKey->delete();

                                                                Activity::event('user:api-key.delete')
                                                                    ->actor($user)
                                                                    ->subject($user)
                                                                    ->subject($apiKey)
                                                                    ->property('identifier', $apiKey->identifier)
                                                                    ->log();
                                                            }

                                                            unset($items[$arguments['item']]);

                                                            $component->state($items);

                                                            $component->callAfterStateUpdated();
                                                        });
                                                    })
                                                    ->schema(fn () => [
                                                        TextEntry::make('memo')
                                                            ->hiddenLabel()
                                                            ->state(fn (ApiKey $key) => $key->memo),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                        Tab::make('ssh_keys')
                            ->label(trans('profile.tabs.ssh_keys'))
                            ->icon('tabler-lock-code')
                            ->schema([
                                Grid::make(5)->schema([
                                    Section::make(trans('profile.create_ssh_key'))->columnSpan(3)
                                        ->schema([
                                            TextInput::make('name')
                                                ->label(trans('profile.name'))
                                                ->live(),
                                            Textarea::make('public_key')
                                                ->label(trans('profile.public_key'))
                                                ->autosize()
                                                ->live(),
                                        ])
                                        ->headerActions([
                                            Action::make('create')
                                                ->label(trans('filament-actions::create.single.modal.actions.create.label'))
                                                ->disabled(fn (Get $get) => empty($get('name')) || empty($get('public_key')))
                                                ->successRedirectUrl(self::getUrl(['tab' => 'ssh-keys::data::tab'], panel: 'app'))
                                                ->action(function (Get $get, Action $action, User $user, KeyCreationService $service) {
                                                    try {
                                                        $sshKey = $service->handle($user, $get('name'), $get('public_key'));

                                                        Activity::event('user:ssh-key.create')
                                                            ->actor($user)
                                                            ->subject($user)
                                                            ->subject($sshKey)
                                                            ->property('fingerprint', $sshKey->fingerprint)
                                                            ->log();

                                                        Notification::make()
                                                            ->title(trans('profile.ssh_key_created'))
                                                            ->body("SHA256:{$sshKey->fingerprint}")
                                                            ->success()
                                                            ->send();

                                                        $action->success();
                                                    } catch (Exception $exception) {
                                                        Notification::make()
                                                            ->title(trans('profile.could_not_create_ssh_key'))
                                                            ->body($exception->getMessage())
                                                            ->danger()
                                                            ->send();

                                                        $action->failure();
                                                    }
                                                }),
                                        ]),
                                    Section::make(trans('profile.ssh_keys'))->columnSpan(2)
                                        ->schema([
                                            Repeater::make('ssh_keys')
                                                ->hiddenLabel()
                                                ->inlineLabel(false)
                                                ->relationship('sshKeys')
                                                ->addable(false)
                                                ->itemLabel(fn ($state) => $state['name'])
                                                ->deleteAction(function (Action $action) {
                                                    $action->requiresConfirmation()->action(function (array $arguments, Repeater $component, User $user) {
                                                        $items = $component->getState();
                                                        $key = $items[$arguments['item']];

                                                        $sshKey = UserSSHKey::find($key['id'] ?? null);
                                                        if ($sshKey->exists()) {
                                                            $sshKey->delete();

                                                            Activity::event('user:ssh-key.delete')
                                                                ->actor($user)
                                                                ->subject($user)
                                                                ->subject($sshKey)
                                                                ->property('fingerprint', $sshKey->fingerprint)
                                                                ->log();
                                                        }

                                                        unset($items[$arguments['item']]);

                                                        $component->state($items);

                                                        $component->callAfterStateUpdated();
                                                    });
                                                })
                                                ->schema(fn () => [
                                                    TextEntry::make('fingerprint')
                                                        ->hiddenLabel()
                                                        ->state(fn (UserSSHKey $key) => "SHA256:{$key->fingerprint}"),
                                                ]),
                                        ]),
                                ]),
                            ]),
                        Tab::make('activity')
                            ->label(trans('profile.tabs.activity'))
                            ->icon('tabler-history')
                            ->schema([
                                Repeater::make('activity')
                                    ->hiddenLabel()
                                    ->inlineLabel(false)
                                    ->deletable(false)
                                    ->addable(false)
                                    ->relationship(null, function (Builder $query) {
                                        $query->orderBy('timestamp', 'desc');
                                    })
                                    ->schema([
                                        TextEntry::make('log')
                                            ->hiddenLabel()
                                            ->state(fn (ActivityLog $log) => new HtmlString($log->htmlable())),
                                    ]),
                            ]),
                        Tab::make('customization')
                            ->label(trans('profile.tabs.customization'))
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
                                        ToggleButtons::make('top_navigation')
                                            ->label(trans('profile.navigation'))
                                            ->inline()
                                            ->options([
                                                1 => trans('profile.top'),
                                                0 => trans('profile.side'),
                                            ])
                                            ->stateCast(new BooleanStateCast(false, true)),
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
                                        TextEntry::make('font_preview')
                                            ->label(trans('profile.font_preview'))
                                            ->columnSpan(2)
                                            ->state(function (Get $get) {
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
                                            ->hintIcon('tabler-question-mark', trans('profile.graph_period_helper'))
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
            ->inlineLabel(!static::isSimple());
    }

    protected function getFormActions(): array
    {
        return [];
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            $this->getSaveFormAction()->formId('form'),
        ];

    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $customization = [
            'console_font' => $data['console_font'],
            'console_font_size' => $data['console_font_size'],
            'console_rows' => $data['console_rows'],
            'console_graph_period' => $data['console_graph_period'],
            'dashboard_layout' => $data['dashboard_layout'],
            'top_navigation' => $data['top_navigation'],
        ];

        unset($data['console_font'],$data['console_font_size'], $data['console_rows'], $data['dashboard_layout'], $data['top_navigation']);

        $data['customization'] = json_encode($customization);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['console_font'] = $this->getUser()->getCustomization(CustomizationKey::ConsoleFont);
        $data['console_font_size'] = (int) $this->getUser()->getCustomization(CustomizationKey::ConsoleFontSize);
        $data['console_rows'] = (int) $this->getUser()->getCustomization(CustomizationKey::ConsoleRows);
        $data['console_graph_period'] = (int) $this->getUser()->getCustomization(CustomizationKey::ConsoleGraphPeriod);
        $data['dashboard_layout'] = $this->getUser()->getCustomization(CustomizationKey::DashboardLayout);
        $data['top_navigation'] = (bool) $this->getUser()->getCustomization(CustomizationKey::TopNavigation);

        return $data;
    }
}
