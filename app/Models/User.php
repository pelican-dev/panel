<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Exceptions\DisplayException;
use App\Extensions\Avatar\AvatarProvider;
use App\Rules\Username;
use App\Facades\Activity;
use App\Traits\HasValidation;
use DateTimeZone;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\In;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\HasAccessTokens;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Storage;
use ResourceBundle;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User.
 *
 * @property int $id
 * @property string|null $external_id
 * @property string $uuid
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string $language
 * @property string $timezone
 * @property bool $use_totp
 * @property string|null $totp_secret
 * @property \Illuminate\Support\Carbon|null $totp_authenticated_at
 * @property string[]|null $oauth
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\ApiKey[] $apiKeys
 * @property int|null $api_keys_count
 * @property string $name
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property int|null $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\RecoveryToken[] $recoveryTokens
 * @property int|null $recovery_tokens_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Server[] $servers
 * @property int|null $servers_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\UserSSHKey[] $sshKeys
 * @property int|null $ssh_keys_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\ApiKey[] $tokens
 * @property int|null $tokens_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property int|null $roles_count
 * @property string|null $customization
 *
 * @method static \Database\Factories\UserFactory factory(...$parameters)
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
 * @method static Builder|User whereTotpAuthenticatedAt($value)
 * @method static Builder|User whereTotpSecret($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUseTotp($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User whereUuid($value)
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, FilamentUser, HasAvatar, HasName, HasTenants, Validatable
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
        'username',
        'email',
        'password',
        'language',
        'timezone',
        'use_totp',
        'totp_secret',
        'totp_authenticated_at',
        'oauth',
        'customization',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = ['password', 'remember_token', 'totp_secret', 'totp_authenticated_at', 'oauth'];

    /**
     * Default values for specific fields in the database.
     */
    protected $attributes = [
        'external_id' => null,
        'language' => 'en',
        'timezone' => 'UTC',
        'use_totp' => false,
        'totp_secret' => null,
        'oauth' => '[]',
        'customization' => null,
    ];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'uuid' => ['nullable', 'string', 'size:36', 'unique:users,uuid'],
        'email' => ['required', 'email', 'between:1,255', 'unique:users,email'],
        'external_id' => ['sometimes', 'nullable', 'string', 'max:255', 'unique:users,external_id'],
        'username' => ['required', 'between:1,255', 'unique:users,username'],
        'password' => ['sometimes', 'nullable', 'string'],
        'language' => ['string'],
        'timezone' => ['string'],
        'use_totp' => ['boolean'],
        'totp_secret' => ['nullable', 'string'],
        'oauth' => ['array', 'nullable'],
        'customization' => ['array', 'nullable'],
        'customization.console_rows' => ['integer', 'min:1'],
        'customization.console_font' => ['string'],
        'customization.console_font_size' => ['integer', 'min:1'],
    ];

    protected function casts(): array
    {
        return [
            'use_totp' => 'boolean',
            'totp_authenticated_at' => 'datetime',
            'totp_secret' => 'encrypted',
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

        $rules['language'][] = new In(array_values(array_filter(ResourceBundle::getLocales(''), fn ($lang) => preg_match('/^[a-z]{2}$/', $lang))));
        $rules['timezone'][] = new In(DateTimeZone::listIdentifiers());
        $rules['username'][] = new Username();

        return $rules;
    }

    public function username(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => mb_strtolower($value),
        );
    }

    public function email(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => mb_strtolower($value),
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

    public function recoveryTokens(): HasMany
    {
        return $this->hasMany(RecoveryToken::class);
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
            });
    }

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

    protected function checkPermission(Server $server, string $permission = ''): bool
    {
        if ($this->canned('update', $server) || $server->owner_id === $this->id) {
            return true;
        }

        // If the user only has "view" permissions allow viewing the console
        if ($permission === Permission::ACTION_WEBSOCKET_CONNECT && $this->canned('view', $server)) {
            return true;
        }

        $subuser = $server->subusers->where('user_id', $this->id)->first();
        if (!$subuser || empty($permission)) {
            return false;
        }

        $check = in_array($permission, $subuser->permissions);

        return $check;
    }

    /**
     * Laravel's policies strictly check for the existence of a real method,
     * this checks if the ability is one of our permissions and then checks if the user can do it or not
     * Otherwise it calls the Authorizable trait's parent method
     *
     * @param  iterable<string|\BackedEnum>|\BackedEnum|string  $abilities
     * @param  array<mixed>|mixed  $arguments
     */
    public function can($abilities, mixed $arguments = []): bool
    {
        if (is_string($abilities) && str_contains($abilities, '.')) {
            [$permission, $key] = str($abilities)->explode('.', 2);

            if (isset(Permission::permissions()[$permission]['keys'][$key])) {
                if ($arguments instanceof Server) {
                    return $this->checkPermission($arguments, $abilities);
                }
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
        if (config('panel.filament.uploadable-avatars')) {
            $path = "avatars/$this->id.png";

            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }

        $provider = AvatarProvider::getProvider(config('panel.filament.avatar-provider'));

        return $provider?->get($this);
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

    /** @return array<mixed> */
    public function getCustomization(): array
    {
        return json_decode($this->customization, true) ?? [];
    }
}
