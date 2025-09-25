<?php

namespace App\Filament\Admin\Resources\Users;

use App\Enums\CustomizationKey;
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
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

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
        return auth()->user()->getCustomization(CustomizationKey::TopNavigation) ? false : trans('admin/dashboard.user');
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
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $user) => auth()->user()->id !== $user->id && !$user->servers_count)
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
                                'md' => 4,
                                'lg' => 4,
                            ])
                            ->schema([
                                TextInput::make('username')
                                    ->label(trans('admin/user.username'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => 1,
                                    ])
                                    ->required()
                                    ->unique()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label(trans('admin/user.email'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
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
                                        'md' => 2,
                                        'lg' => 1,
                                    ])
                                    ->hintIcon(fn ($operation) => $operation === 'create' ? 'tabler-question-mark' : null, fn ($operation) => $operation === 'create' ? trans('admin/user.password_help') : null)
                                    ->password(),
                                TextInput::make('external_id')
                                    ->label(trans('admin/user.external_id'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => 1,
                                    ]),
                                Select::make('timezone')
                                    ->label(trans('profile.timezone'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => 2,
                                    ])
                                    ->required()
                                    ->prefixIcon('tabler-clock-pin')
                                    ->default('UTC')
                                    ->selectablePlaceholder(false)
                                    ->options(fn () => collect(DateTimeZone::listIdentifiers())->mapWithKeys(fn ($tz) => [$tz => $tz]))
                                    ->searchable()
                                    ->native(false),
                                Select::make('language')
                                    ->label(trans('profile.language'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => 2,
                                    ])
                                    ->required()
                                    ->prefixIcon('tabler-flag')
                                    ->live()
                                    ->default('en')
                                    ->searchable()
                                    ->selectablePlaceholder(false)
                                    ->helperText(fn ($state, LanguageService $languageService) => new HtmlString($languageService->isLanguageTranslated($state) ? ''
                                        : trans('profile.language_help', ['state' => $state]) . ' <u><a href="https://crowdin.com/project/pelican-dev/">Update On Crowdin</a></u>'))
                                    ->options(fn (LanguageService $languageService) => $languageService->getAvailableLanguages())
                                    ->native(false),
                                FileUpload::make('avatar')
                                    ->visible(fn () => config('panel.filament.uploadable-avatars'))
                                    ->avatar()
                                    ->disabled()
                                    ->acceptedFileTypes(['image/png'])
                                    ->directory('avatars')
                                    ->disk('public')
                                    ->getUploadedFileNameForStorageUsing(fn (User $user) => $user->id . '.png')
                                    ->hintAction(function (FileUpload $fileUpload, User $user) {
                                        $path = $fileUpload->getDirectory() . '/' . $user->id . '.png';

                                        return Action::make('remove_avatar')
                                            ->icon('tabler-photo-minus')
                                            ->iconButton()
                                            ->hidden(fn () => !$fileUpload->getDisk()->exists($path))
                                            ->action(fn () => $fileUpload->getDisk()->delete($path));
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
                            ->label(trans('profile.tabs.keys'))
                            ->icon('tabler-key')
                            ->schema([
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
                        Tab::make('activity') //TODO: Make it work.
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
                        //                        Tab::make('customization')
                        //                            ->label(trans('profile.tabs.customization'))
                        //                            ->icon('tabler-adjustments')
                        //                            ->schema([
                        //                                Section::make(trans('profile.dashboard'))
                        //                                    ->collapsible()
                        //                                    ->icon('tabler-dashboard')
                        //                                    ->columns()
                        //                                    ->schema([
                        //                                        ToggleButtons::make('dashboard_layout')
                        //                                            ->label(trans('profile.dashboard_layout'))
                        //                                            ->inline()
                        //                                            ->default('grid')
                        //                                            ->options([
                        //                                                'grid' => trans('profile.grid'),
                        //                                                'table' => trans('profile.table'),
                        //                                            ]),
                        //                                        ToggleButtons::make('top_navigation')
                        //                                            ->label(trans('profile.navigation'))
                        //                                            ->inline()
                        //                                            ->options([
                        //                                                1 => trans('profile.top'),
                        //                                                0 => trans('profile.side'),
                        //                                            ])
                        //                                            ->stateCast(new BooleanStateCast(false, true)),
                        //                                    ]),
                        //                                Section::make(trans('profile.console'))
                        //                                    ->collapsible()
                        //                                    ->icon('tabler-brand-tabler')
                        //                                    ->columns(4)
                        //                                    ->schema([
                        //                                        TextInput::make('console_font_size')
                        //                                            ->label(trans('profile.font_size'))
                        //                                            ->columnSpan(1)
                        //                                            ->minValue(1)
                        //                                            ->numeric()
                        //                                            ->required()
                        //                                            ->default(14),
                        //                                        Select::make('console_font')
                        //                                            ->label(trans('profile.font'))
                        //                                            ->required()
                        //                                            ->default('sans-serif')
                        //                                            ->options(function () {
                        //                                                $fonts = [
                        //                                                    'monospace' => 'monospace', //default
                        //                                                ];
                        //
                        //                                                if (!Storage::disk('public')->exists('fonts')) {
                        //                                                    Storage::disk('public')->makeDirectory('fonts');
                        //                                                    $this->fillForm();
                        //                                                }
                        //
                        //                                                foreach (Storage::disk('public')->allFiles('fonts') as $file) {
                        //                                                    $fileInfo = pathinfo($file);
                        //
                        //                                                    if ($fileInfo['extension'] === 'ttf') {
                        //                                                        $fonts[$fileInfo['filename']] = $fileInfo['filename'];
                        //                                                    }
                        //                                                }
                        //
                        //                                                return $fonts;
                        //                                            })
                        //                                            ->reactive()
                        //                                            ->default('monospace')
                        //                                            ->afterStateUpdated(fn ($state, Set $set) => $set('font_preview', $state)),
                        //                                        TextEntry::make('font_preview')
                        //                                            ->label(trans('profile.font_preview'))
                        //                                            ->columnSpan(2)
                        //                                            ->state(function (Get $get) {
                        //                                                $fontName = $get('console_font') ?? 'monospace';
                        //                                                $fontSize = $get('console_font_size') . 'px';
                        //                                                $style = <<<CSS
                        //                                                            .preview-text {
                        //                                                                font-family: $fontName;
                        //                                                                font-size: $fontSize;
                        //                                                                margin-top: 10px;
                        //                                                                display: block;
                        //                                                            }
                        //                                                        CSS;
                        //                                                if ($fontName !== 'monospace') {
                        //                                                    $fontUrl = asset("storage/fonts/$fontName.ttf");
                        //                                                    $style = <<<CSS
                        //                                                                @font-face {
                        //                                                                    font-family: $fontName;
                        //                                                                    src: url("$fontUrl");
                        //                                                                }
                        //                                                                $style
                        //                                                            CSS;
                        //                                                }
                        //
                        //                                                return new HtmlString(<<<HTML
                        //                                                            <style>
                        //                                                            {$style}
                        //                                                            </style>
                        //                                                            <span class="preview-text">The quick blue pelican jumps over the lazy pterodactyl. :)</span>
                        //                                                        HTML);
                        //                                            }),
                        //                                        TextInput::make('console_graph_period')
                        //                                            ->label(trans('profile.graph_period'))
                        //                                            ->suffix(trans('profile.seconds'))
                        //                                            ->hintIcon('tabler-question-mark', trans('profile.graph_period_helper'))
                        //                                            ->columnSpan(2)
                        //                                            ->numeric()
                        //                                            ->default(30)
                        //                                            ->minValue(10)
                        //                                            ->maxValue(120)
                        //                                            ->required(),
                        //                                        TextInput::make('console_rows')
                        //                                            ->label(trans('profile.rows'))
                        //                                            ->minValue(1)
                        //                                            ->numeric()
                        //                                            ->required()
                        //                                            ->columnSpan(2)
                        //                                            ->default(30),
                        //                                    ]),
                        //                            ]),
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
