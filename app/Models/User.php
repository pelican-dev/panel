<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Enums\CustomizationKey;
use App\Enums\SubuserPermission;
use App\Exceptions\DisplayException;
use App\Extensions\Avatar\AvatarService;
use App\Models\Traits\HasAccessTokens;
use App\Traits\HasValidation;
use BackedEnum;
use Database\Factories\UserFactory;
use DateTimeZone;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\In;
use ResourceBundle;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User.
 *
 * @property int $id
 * @property string|null $external_id
 * @property bool $is_managed_externally
 * @property string $uuid
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string $language
 * @property string $timezone
 * @property string[]|null $oauth
 * @property string|null $mfa_app_secret
 * @property string[]|null $mfa_app_recovery_codes
 * @property bool $mfa_email_enabled
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|ApiKey[] $apiKeys
 * @property int|null $api_keys_count
 * @property DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property int|null $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection|Server[] $servers
 * @property int|null $servers_count
 * @property \Illuminate\Database\Eloquent\Collection|UserSSHKey[] $sshKeys
 * @property int|null $ssh_keys_count
 * @property \Illuminate\Database\Eloquent\Collection|ApiKey[] $tokens
 * @property int|null $tokens_count
 * @property \Illuminate\Database\Eloquent\Collection|Role[] $roles
 * @property int|null $roles_count
 * @property string|array<string, mixed>|null $customization
 *
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereExternalId($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLanguage($value)
 * @method static Builder|User whereTimezone($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User whereUuid($value)
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, HasAvatar, HasEmailAuthentication, HasName, HasTenants, Validatable
{
    use Authenticatable;
    use Authorizable { can as protected canned; }
    use CanResetPassword;
    use HasAccessTokens;
    use HasFactory;
    use HasRoles;
    use HasValidation { getRules as getValidationRules; }
    use Notifiable;

    public const USER_LEVEL_USER = 0;

    public const USER_LEVEL_ADMIN = 1;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal. Also used as name for api key permissions.
     */
    public const RESOURCE_NAME = 'user';

    /**
     * A list of mass-assignable variables.
     */
    protected $fillable = [
        'external_id',
        'is_managed_externally',
        'username',
        'email',
        'password',
        'language',
        'timezone',
        'mfa_app_secret',
        'mfa_app_recovery_codes',
        'mfa_email_enabled',
        'oauth',
        'customization',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = ['password', 'remember_token', 'mfa_app_secret', 'mfa_app_recovery_codes', 'oauth'];

    /**
     * Default values for specific fields in the database.
     */
    protected $attributes = [
        'external_id' => null,
        'is_managed_externally' => false,
        'language' => 'en',
        'timezone' => 'UTC',
        'mfa_app_secret' => null,
        'mfa_app_recovery_codes' => null,
        'mfa_email_enabled' => false,
        'oauth' => '[]',
        'customization' => null,
    ];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'uuid' => ['nullable', 'string', 'size:36', 'unique:users,uuid'],
        'email' => ['required', 'email', 'between:1,255', 'unique:users,email'],
        'external_id' => ['sometimes', 'nullable', 'string', 'max:255', 'unique:users,external_id'],
        'is_managed_externally' => ['boolean'],
        'username' => ['required', 'between:1,255', 'unique:users,username'],
        'password' => ['sometimes', 'nullable', 'string'],
        'language' => ['string'],
        'timezone' => ['string'],
        'mfa_app_secret' => ['nullable', 'string'],
        'mfa_app_recovery_codes' => ['nullable', 'array'],
        'mfa_app_recovery_codes.*' => ['string'],
        'mfa_email_enabled' => ['boolean'],
        'oauth' => ['array', 'nullable'],
        'customization' => ['array', 'nullable'],
        'customization.console_rows' => ['integer', 'min:1'],
        'customization.console_font' => ['string'],
        'customization.console_font_size' => ['integer', 'min:1'],
        'customization.console_graph_period' => ['integer', 'min:1'],
        'customization.top_navigation' => ['boolean'],
        'customization.dashboard_layout' => ['string', 'in:grid,table'],
    ];

    protected function casts(): array
    {
        return [
            'is_managed_externally' => 'boolean',
            'mfa_app_secret' => 'encrypted',
            'mfa_app_recovery_codes' => 'encrypted:array',
            'oauth' => 'array',
            'customization' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $user) {
            $user->uuid ??= Str::uuid()->toString();
            $user->timezone ??= config('app.timezone');

            return true;
        });

        static::saving(function (self $user) {
            $user->email = mb_strtolower($user->email);
        });

        static::deleting(function (self $user) {
            throw_if($user->servers()->count() > 0, new DisplayException(trans('exceptions.users.has_servers')));

            throw_if(request()->user()?->id === $user->id, new DisplayException(trans('exceptions.users.is_self')));
        });
    }

    /**
     * Implement language verification by overriding Eloquence's gather
     * rules function.
     */
    public static function getRules(): array
    {
        $rules = self::getValidationRules();

        $rules['language'][] = new In(ResourceBundle::getLocales(''));
        $rules['timezone'][] = new In(DateTimeZone::listIdentifiers());

        return $rules;
    }

    public function username(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => str($value)->lower()->trim()->toString(),
        );
    }

    public function email(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => str($value)->lower()->trim()->toString(),
        );
    }

    /**
     * Returns all servers that a user owns.
     *
     * @return HasMany<Server, $this>
     */
    public function servers(): HasMany
    {
        return $this->hasMany(Server::class, 'owner_id');
    }

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class)
            ->where('key_type', ApiKey::TYPE_ACCOUNT);
    }

    public function sshKeys(): HasMany
    {
        return $this->hasMany(UserSSHKey::class);
    }

    /**
     * Returns all the activity logs where this user is the subject — not to
     * be confused by activity logs where this user is the _actor_.
     */
    public function activity(): MorphToMany
    {
        return $this->morphToMany(ActivityLog::class, 'subject', 'activity_log_subjects');
    }

    /**
     * Returns all the servers that a user can access.
     * Either because they are an admin or because they are the owner/ a subuser of the server.
     */
    public function accessibleServers(): Builder
    {
        if ($this->canned('viewAny', Server::class)) {
            return Server::select('servers.*')
                ->leftJoin('subusers', 'subusers.server_id', '=', 'servers.id')
                ->where(function (Builder $builder) {
                    $builder->where('servers.owner_id', $this->id)->orWhere('subusers.user_id', $this->id)->orWhereIn('servers.node_id', $this->accessibleNodes()->pluck('id'));
                })
                ->distinct('servers.id');
        }

        return $this->directAccessibleServers();
    }

    /**
     * Returns all the servers that a user can access "directly".
     * This means either because they are the owner or a subuser of the server.
     */
    public function directAccessibleServers(): Builder
    {
        return Server::select('servers.*')
            ->leftJoin('subusers', 'subusers.server_id', '=', 'servers.id')
            ->where(function (Builder $builder) {
                $builder->where('servers.owner_id', $this->id)->orWhere('subusers.user_id', $this->id);
            })
            ->distinct('servers.id');
    }

    /** @return Builder<Node> */
    public function accessibleNodes(): Builder
    {
        // Root admins can access all nodes
        if ($this->isRootAdmin()) {
            return Node::query();
        }

        // Check if there are no restrictions from any role
        $roleIds = $this->roles()->pluck('id');
        if (!NodeRole::whereIn('role_id', $roleIds)->exists()) {
            return Node::query();
        }

        return Node::whereHas('roles', fn (Builder $builder) => $builder->whereIn('roles.id', $roleIds));
    }

    public function subusers(): HasMany
    {
        return $this->hasMany(Subuser::class);
    }

    public function subServers(): BelongsToMany
    {
        return $this->belongsToMany(Server::class, 'subusers');
    }

    /** @return ($key is null ? array<string, string|int|bool> : string|int|bool) */
    public function getCustomization(?CustomizationKey $key = null): array|string|int|bool|null
    {
        $customization = (is_string($this->customization) ? json_decode($this->customization, true) : $this->customization) ?? [];
        $customization = array_merge(CustomizationKey::getDefaultCustomization(), $customization);

        return !$key ? $customization : $customization[$key->value];
    }

    protected function hasPermission(Server $server, string $permission = ''): bool
    {
        if ($this->canned('update', $server) || $server->owner_id === $this->id) {
            return true;
        }

        // If the user only has "view" permissions allow viewing the console
        if ($permission === SubuserPermission::WebsocketConnect->value && $this->canned('view', $server)) {
            return true;
        }

        $subuser = $server->subusers->where('user_id', $this->id)->first();
        if (!$subuser || empty($permission)) {
            return false;
        }

        return in_array($permission, $subuser->permissions);
    }

    protected function checkPermission(Server $server, string|SubuserPermission $permission = ''): bool
    {
        if ($permission instanceof SubuserPermission) {
            $permission = $permission->value;
        }

        $contextKey = "users.$this->id.servers.$server->id.$permission";

        return Context::remember($contextKey, fn () => $this->hasPermission($server, $permission));
    }

    /**
     * Laravel's policies strictly check for the existence of a real method,
     * this checks if the ability is one of our permissions and then checks if the user can do it or not
     * Otherwise it calls the Authorizable trait's parent method
     *
     * @param  iterable<string|BackedEnum>|BackedEnum|string  $abilities
     * @param  array<mixed>|mixed  $arguments
     */
    public function can($abilities, mixed $arguments = []): bool
    {
        if ($arguments instanceof Server) {
            if ($abilities instanceof SubuserPermission || Subuser::doesPermissionExist($abilities)) {
                return $this->checkPermission($arguments, $abilities);
            }
        }

        return $this->canned($abilities, $arguments);
    }

    public function isLastRootAdmin(): bool
    {
        $rootAdmins = User::all()->filter(fn ($user) => $user->isRootAdmin());

        return once(fn () => $rootAdmins->count() === 1 && $rootAdmins->first()->is($this));
    }

    public function isRootAdmin(): bool
    {
        return $this->hasRole(Role::ROOT_ADMIN);
    }

    public function isAdmin(): bool
    {
        return $this->isRootAdmin() || ($this->roles()->count() >= 1 && $this->getAllPermissions()->count() >= 1);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->isRootAdmin()) {
            return true;
        }

        if ($panel->getId() === 'admin') {
            return $this->isAdmin();
        }

        return true;
    }

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return App::call(fn (AvatarService $service) => $service->getAvatarUrl($this));
    }

    public function canTarget(Model $model): bool
    {
        // Root admins can target everyone and everything
        if ($this->isRootAdmin()) {
            return true;
        }

        // Make sure normal admins can't target root admins
        if ($model instanceof User) {
            return !$model->isRootAdmin();
        }

        // Make sure the user can only target accessible nodes
        if ($model instanceof Node) {
            return $this->accessibleNodes()->where('id', $model->id)->exists();
        }

        return false;
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->accessibleServers()->get();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($tenant instanceof Server) {
            if ($this->canned('view', $tenant) || $tenant->owner_id === $this->id) {
                return true;
            }

            $subuser = $tenant->subusers->where('user_id', $this->id)->first();

            return $subuser !== null;
        }

        return false;
    }

    public function getAppAuthenticationSecret(): ?string
    {
        return $this->mfa_app_secret;
    }

    public function saveAppAuthenticationSecret(?string $secret): void
    {
        $this->update(['mfa_app_secret' => $secret]);
    }

    public function getAppAuthenticationHolderName(): string
    {
        return $this->email;
    }

    /**
     * @return array<string>|null
     */
    public function getAppAuthenticationRecoveryCodes(): ?array
    {
        return $this->mfa_app_recovery_codes;
    }

    /**
     * @param  array<string>|null  $codes
     */
    public function saveAppAuthenticationRecoveryCodes(?array $codes): void
    {
        $this->update(['mfa_app_recovery_codes' => $codes]);
    }

    public function hasEmailAuthentication(): bool
    {
        return $this->mfa_email_enabled;
    }

    public function toggleEmailAuthentication(bool $condition): void
    {
        $this->update(['mfa_email_enabled' => $condition]);
    }
}
