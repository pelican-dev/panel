<?php

namespace App\Filament\Admin\Resources\Users;

use App\Enums\CustomizationKey;
use App\Extensions\OAuth\OAuthService;
use App\Facades\Activity;
use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Pages\ViewUser;
use App\Filament\Admin\Resources\Users\RelationManagers\ServersRelationManager;
use App\Models\ActivityLog;
use App\Models\ApiKey;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSSHKey;
use App\Services\Helpers\LanguageService;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyForm;
use App\Traits\Filament\CanModifyTable;
use DateTimeZone;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Auth\Notifications\ResetPassword;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Auth\Events\PasswordResetLinkSent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyForm;
    use CanModifyTable;

    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-users';

    protected static ?string $recordTitleAttribute = 'username';

    public static function getNavigationLabel(): string
    {
        return trans('admin/user.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/user.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/user.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return user()?->getCustomization(CustomizationKey::TopNavigation) ? false : trans('admin/dashboard.user');
    }

    public static function getNavigationBadge(): ?string
    {
        return ($count = static::getModel()::count()) > 0 ? (string) $count : null;
    }

    /**
     * @throws Exception
     */
    public static function defaultTable(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('picture')
                    ->visibleFrom('lg')
                    ->label('')
                    ->circular()
                    ->alignCenter()
                    ->defaultImageUrl(fn (User $user) => Filament::getUserAvatarUrl($user)),
                TextColumn::make('username')
                    ->label(trans('admin/user.username'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(trans('admin/user.email'))
                    ->searchable(),
                IconColumn::make('mfa_email_enabled')
                    ->label(trans('profile.tabs.2fa'))
                    ->visibleFrom('lg')
                    ->icon(fn (User $user) => filled($user->mfa_app_secret) ? 'tabler-qrcode' : ($user->mfa_email_enabled ? 'tabler-mail' : 'tabler-lock-open-off'))
                    ->tooltip(fn (User $user) => filled($user->mfa_app_secret) ? 'App' : ($user->mfa_email_enabled ? 'E-Mail' : 'None')),
                TextColumn::make('roles.name')
                    ->label(trans('admin/user.roles'))
                    ->badge()
                    ->placeholder(trans('admin/user.no_roles')),
                TextColumn::make('servers_count')
                    ->counts('servers')
                    ->label(trans('admin/user.servers')),
                TextColumn::make('subusers_count')
                    ->visibleFrom('sm')
                    ->label(trans('admin/user.subusers'))
                    ->counts('subusers'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::getEditAuthorizationResponse($record)->allowed()),
                EditAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $user) => user()?->id !== $user->id && !$user->servers_count)
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3, 'md' => 2])
            ->components([
                Tabs::make()
                    ->schema([
                        Tab::make('account')
                            ->label(trans('profile.tabs.account'))
                            ->icon('tabler-user-cog')
                            ->columns([
                                'default' => 1,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('username')
                                    ->label(trans('admin/user.username'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ])
                                    ->required()
                                    ->unique()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label(trans('admin/user.email'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ])
                                    ->email()
                                    ->required()
                                    ->unique()
                                    ->maxLength(255),
                                TextInput::make('password')
                                    ->label(trans('admin/user.password'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ])
                                    ->hintIcon(fn ($operation) => $operation === 'create' ? 'tabler-question-mark' : null, fn ($operation) => $operation === 'create' ? trans('admin/user.password_help') : null)
                                    ->password()
                                    ->hintAction(
                                        Action::make('password_reset')
                                            ->label(trans('admin/user.password_reset'))
                                            ->hidden(fn (string $operation) => $operation === 'create' || config('mail.default', 'log') === 'log')
                                            ->icon('tabler-send')
                                            ->action(function (User $user) {
                                                $status = Password::broker(Filament::getPanel('app')->getAuthPasswordBroker())->sendResetLink([
                                                    'email' => $user->email,
                                                ],
                                                    function (User $user, string $token) {
                                                        $notification = new ResetPassword($token);
                                                        $notification->url = Filament::getPanel('app')->getResetPasswordUrl($token, $user);

                                                        $user->notify($notification);

                                                        event(new PasswordResetLinkSent($user));
                                                    },
                                                );

                                                if ($status === Password::RESET_LINK_SENT) {
                                                    Notification::make()
                                                        ->title(trans('admin/user.password_reset_sent'))
                                                        ->success()
                                                        ->send();
                                                } else {
                                                    Notification::make()
                                                        ->title(trans('admin/user.password_reset_failed'))
                                                        ->body($status)
                                                        ->danger()
                                                        ->send();
                                                }
                                            })),
                                TextInput::make('external_id')
                                    ->label(trans('admin/user.external_id'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ]),
                                Toggle::make('is_managed_externally')
                                    ->label(trans('admin/user.is_managed_externally'))
                                    ->hintIcon('tabler-question-mark', trans('admin/user.is_managed_externally_helper'))
                                    ->inline(false)
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ]),
                                Section::make(trans('profile.tabs.customization'))
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->columns(2)
                                    ->schema([
                                        Select::make('timezone')
                                            ->label(trans('profile.timezone'))
                                            ->required()
                                            ->prefixIcon('tabler-clock-pin')
                                            ->default(fn () => config('app.timezone', 'UTC'))
                                            ->selectablePlaceholder(false)
                                            ->options(fn () => collect(DateTimeZone::listIdentifiers())->mapWithKeys(fn ($tz) => [$tz => $tz]))
                                            ->searchable(),
                                        Select::make('language')
                                            ->label(trans('profile.language'))
                                            ->required()
                                            ->prefixIcon('tabler-flag')
                                            ->live()
                                            ->default('en')
                                            ->searchable()
                                            ->selectablePlaceholder(false)
                                            ->options(fn (LanguageService $languageService) => $languageService->getAvailableLanguages()),
                                        FileUpload::make('avatar')
                                            ->visible(fn (?User $user, FileUpload $fileUpload) => $user ? $fileUpload->getDisk()->exists($fileUpload->getDirectory() . '/' . $user->id . '.png') : false)
                                            ->columnSpanFull()
                                            ->avatar()
                                            ->directory('avatars')
                                            ->disk('public')
                                            ->formatStateUsing(function (FileUpload $fileUpload, ?User $user) {
                                                if (!$user) {
                                                    return null;
                                                }
                                                $path = $fileUpload->getDirectory() . '/' . $user->id . '.png';
                                                if ($fileUpload->getDisk()->exists($path)) {
                                                    return $path;
                                                }
                                            })
                                            ->deleteUploadedFileUsing(function (FileUpload $fileUpload, $file) {
                                                if ($file instanceof TemporaryUploadedFile) {
                                                    return $file->delete();
                                                }

                                                if ($fileUpload->getDisk()->exists($file)) {
                                                    return $fileUpload->getDisk()->delete($file);
                                                }
                                            }),
                                    ]),
                                Section::make(trans('profile.tabs.oauth'))
                                    ->visible(fn (?User $user) => $user)
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->schema(function (OAuthService $oauthService, ?User $user) {
                                        if (!$user) {
                                            return;
                                        }

                                        $actions = [];
                                        foreach ($user->oauth ?? [] as $schema => $_) {
                                            $schema = $oauthService->get($schema);
                                            if (!$schema) {
                                                return;
                                            }

                                            $id = $schema->getId();
                                            $name = $schema->getName();

                                            $color = $schema->getHexColor();
                                            $color = is_string($color) ? Color::hex($color) : null;

                                            $actions[] = Action::make("oauth_$id")
                                                ->label(trans('profile.unlink', ['name' => $name]))
                                                ->icon('tabler-unlink')
                                                ->requiresConfirmation()
                                                ->color($color)
                                                ->action(function ($livewire) use ($oauthService, $user, $name, $schema) {
                                                    $oauthService->unlinkUser($user, $schema);
                                                    $livewire->form->fill($user->attributesToArray());
                                                    Notification::make()
                                                        ->title(trans('profile.unlinked', ['name' => $name]))
                                                        ->success()
                                                        ->send();
                                                });
                                        }

                                        if (!$actions) {
                                            return [
                                                TextEntry::make('no_oauth')
                                                    ->state(trans('profile.no_oauth'))
                                                    ->hiddenLabel(),
                                            ];
                                        }

                                        return [Actions::make($actions)];
                                    }),
                            ]),
                        Tab::make('roles')
                            ->label(trans('admin/user.roles'))
                            ->icon('tabler-users-group')
                            ->components([
                                CheckboxList::make('roles')
                                    ->hidden(fn (?User $user) => $user && $user->isRootAdmin())
                                    ->relationship('roles', 'name', fn (Builder $query) => $query->whereNot('id', Role::getRootAdmin()->id))
                                    ->saveRelationshipsUsing(fn (User $user, array $state) => $user->syncRoles(collect($state)->map(fn ($role) => Role::findById($role))))
                                    ->dehydrated()
                                    ->label(trans('admin/user.admin_roles'))
                                    ->columnSpanFull()
                                    ->bulkToggleable(false),
                                CheckboxList::make('root_admin_role')
                                    ->visible(fn (?User $user) => $user && $user->isRootAdmin())
                                    ->disabled()
                                    ->options([
                                        'root_admin' => Role::ROOT_ADMIN,
                                    ])
                                    ->descriptions([
                                        'root_admin' => trans('admin/role.root_admin', ['role' => Role::ROOT_ADMIN]),
                                    ])
                                    ->formatStateUsing(fn () => ['root_admin'])
                                    ->dehydrated(false)
                                    ->label(trans('admin/user.admin_roles'))
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('keys')
                            ->visible(fn (?User $user) => $user)
                            ->label(trans('profile.tabs.keys'))
                            ->icon('tabler-key')
                            ->schema([
                                Section::make(trans('profile.api_keys'))
                                    ->columnSpan(2)
                                    ->schema([
                                        Repeater::make('api_keys')
                                            ->hiddenLabel()
                                            ->inlineLabel(false)
                                            ->relationship('apiKeys')
                                            ->addable(false)
                                            ->itemLabel(fn ($state) => $state['identifier'])
                                            ->deleteAction(function (Action $action) {
                                                $action->requiresConfirmation()->action(function (array $arguments, Repeater $component, ?User $user) {
                                                    $items = $component->getState();
                                                    $key = $items[$arguments['item']] ?? null;

                                                    if ($key) {
                                                        $apiKey = ApiKey::find($key['id']);
                                                        if ($apiKey?->exists()) {
                                                            $apiKey->delete();

                                                            Activity::event('user:api-key.delete')
                                                                ->actor(user())
                                                                ->subject($user)
                                                                ->subject($apiKey)
                                                                ->property('identifier', $apiKey->identifier)
                                                                ->log();
                                                        }

                                                        unset($items[$arguments['item']]);
                                                        $component->state($items);
                                                        $component->callAfterStateUpdated();
                                                    }
                                                });
                                            })
                                            ->schema([
                                                TextEntry::make('memo')
                                                    ->hiddenLabel()
                                                    ->state(fn (ApiKey $key) => $key->memo),
                                            ])
                                            ->visible(fn (User $user) => $user->apiKeys()->exists()),

                                        TextEntry::make('no_api_keys')
                                            ->state(trans('profile.no_api_keys'))
                                            ->hiddenLabel()
                                            ->visible(fn (User $user) => !$user->apiKeys()->exists()),
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
                                                            ->actor(user())
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
                                            ])
                                            ->visible(fn (User $user) => $user->sshKeys()->exists()),

                                        TextEntry::make('no_ssh_keys')
                                            ->state(trans('profile.no_ssh_keys'))
                                            ->hiddenLabel()
                                            ->visible(fn (User $user) => !$user->sshKeys()->exists()),
                                    ]),
                            ]),
                        Tab::make('activity')
                            ->visible(fn (?User $user) => $user)
                            ->disabledOn('create')
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
                    ])->columnSpanFull(),
            ]);
    }

    /** @return class-string<RelationManager>[] */
    public static function getDefaultRelations(): array
    {
        return [
            ServersRelationManager::class,
        ];
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
