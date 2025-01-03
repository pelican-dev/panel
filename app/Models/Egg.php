<?php

namespace App\Models;

use App\Exceptions\Service\Egg\HasChildrenException;
use App\Exceptions\Service\HasActiveServersException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $author
 * @property string $name
 * @property string|null $description
 * @property array|null $features
 * @property string $docker_image -- deprecated, use $docker_images
 * @property array<string, string> $docker_images
 * @property string|null $update_url
 * @property bool $force_outgoing_ip
 * @property array|null $file_denylist
 * @property string|null $config_files
 * @property string|null $config_startup
 * @property string|null $config_logs
 * @property string|null $config_stop
 * @property int|null $config_from
 * @property string|null $startup
 * @property bool $script_is_privileged
 * @property string|null $script_install
 * @property string $script_entry
 * @property string $script_container
 * @property int|null $copy_script_from
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string|null $copy_script_install
 * @property string $copy_script_entry
 * @property string $copy_script_container
 * @property string|null $inherit_config_files
 * @property string|null $inherit_config_startup
 * @property string|null $inherit_config_logs
 * @property string|null $inherit_config_stop
 * @property string $inherit_file_denylist
 * @property array|null $inherit_features
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Server[] $servers
 * @property int|null $servers_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\EggVariable[] $variables
 * @property int|null $variables_count
 * @property \App\Models\Egg|null $scriptFrom
 * @property \App\Models\Egg|null $configFrom
 */
class Egg extends Model
{
    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal. Also used as name for api key permissions.
     */
    public const RESOURCE_NAME = 'egg';

    /**
     * Defines the current egg export version.
     */
    public const EXPORT_VERSION = 'PLCN_v1';

    /**
     * Different features that can be enabled on any given egg. These are used internally
     * to determine which types of frontend functionality should be shown to the user. Eggs
     * will automatically inherit features from a parent egg if they are already configured
     * to copy configuration values from said egg.
     *
     * To skip copying the features, an empty array value should be passed in ("[]") rather
     * than leaving it null.
     */
    public const FEATURE_EULA_POPUP = 'eula';

    public const FEATURE_FASTDL = 'fastdl';

    /**
     * The table associated with the model.
     */
    protected $table = 'eggs';

    /**
     * Fields that are not mass assignable.
     */
    protected $fillable = [
        'uuid',
        'name',
        'author',
        'description',
        'features',
        'docker_images',
        'force_outgoing_ip',
        'file_denylist',
        'config_files',
        'config_startup',
        'config_logs',
        'config_stop',
        'config_from',
        'startup',
        'update_url',
        'script_is_privileged',
        'script_install',
        'script_entry',
        'script_container',
        'copy_script_from',
        'tags',
    ];

    public static array $validationRules = [
        'uuid' => 'required|string|size:36',
        'name' => 'required|string|max:255',
        'description' => 'string|nullable',
        'features' => 'array|nullable',
        'author' => 'required|string|email',
        'file_denylist' => 'array|nullable',
        'file_denylist.*' => 'string',
        'docker_images' => 'required|array|min:1',
        'docker_images.*' => 'required|string',
        'startup' => 'required|nullable|string',
        'config_from' => 'sometimes|bail|nullable|numeric|exists:eggs,id',
        'config_stop' => 'required_without:config_from|nullable|string|max:255',
        'config_startup' => 'required_without:config_from|nullable|json',
        'config_logs' => 'required_without:config_from|nullable|json',
        'config_files' => 'required_without:config_from|nullable|json',
        'update_url' => 'sometimes|nullable|string',
        'force_outgoing_ip' => 'sometimes|boolean',
    ];

    protected $attributes = [
        'features' => null,
        'file_denylist' => null,
        'config_stop' => null,
        'config_startup' => null,
        'config_logs' => null,
        'config_files' => null,
        'update_url' => null,
        'tags' => '[]',
    ];

    protected function casts(): array
    {
        return [
            'config_from' => 'integer',
            'script_is_privileged' => 'boolean',
            'force_outgoing_ip' => 'boolean',
            'copy_script_from' => 'integer',
            'features' => 'array',
            'docker_images' => 'array',
            'file_denylist' => 'array',
            'tags' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $egg) {
            $egg->uuid ??= Str::uuid()->toString();

            return true;
        });

        static::deleting(function (self $egg) {
            throw_if($egg->servers()->count(), new HasActiveServersException(trans('exceptions.egg.delete_has_servers')));

            throw_if($egg->children()->count(), new HasChildrenException(trans('exceptions.egg.has_children')));
        });
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Returns the install script for the egg; if egg is copying from another
     * it will return the copied script.
     */
    public function getCopyScriptInstallAttribute(): ?string
    {
        if (!empty($this->script_install) || empty($this->copy_script_from)) {
            return $this->script_install;
        }

        return $this->scriptFrom->script_install;
    }

    /**
     * Returns the entry command for the egg; if egg is copying from another
     * it will return the copied entry command.
     */
    public function getCopyScriptEntryAttribute(): string
    {
        if (!empty($this->script_entry) || empty($this->copy_script_from)) {
            return $this->script_entry;
        }

        return $this->scriptFrom->script_entry;
    }

    /**
     * Returns the install container for the egg; if egg is copying from another
     * it will return the copied install container.
     */
    public function getCopyScriptContainerAttribute(): string
    {
        if (!empty($this->script_container) || empty($this->copy_script_from)) {
            return $this->script_container;
        }

        return $this->scriptFrom->script_container;
    }

    /**
     * Return the file configuration for an egg.
     */
    public function getInheritConfigFilesAttribute(): ?string
    {
        if (!is_null($this->config_files) || is_null($this->config_from)) {
            return $this->config_files;
        }

        return $this->configFrom->config_files;
    }

    /**
     * Return the startup configuration for an egg.
     */
    public function getInheritConfigStartupAttribute(): ?string
    {
        if (!is_null($this->config_startup) || is_null($this->config_from)) {
            return $this->config_startup;
        }

        return $this->configFrom->config_startup;
    }

    /**
     * Return the log reading configuration for an egg.
     */
    public function getInheritConfigLogsAttribute(): ?string
    {
        if (!is_null($this->config_logs) || is_null($this->config_from)) {
            return $this->config_logs;
        }

        return $this->configFrom->config_logs;
    }

    /**
     * Return the stop command configuration for an egg.
     */
    public function getInheritConfigStopAttribute(): ?string
    {
        if (!is_null($this->config_stop) || is_null($this->config_from)) {
            return $this->config_stop;
        }

        return $this->configFrom->config_stop;
    }

    /**
     * Returns the features available to this egg from the parent configuration if there are
     * no features defined for this egg specifically and there is a parent egg configured.
     */
    public function getInheritFeaturesAttribute(): ?array
    {
        if (!is_null($this->features) || is_null($this->config_from)) {
            return $this->features;
        }

        return $this->configFrom->features;
    }

    /**
     * Returns the features available to this egg from the parent configuration if there are
     * no features defined for this egg specifically and there is a parent egg configured.
     */
    public function getInheritFileDenylistAttribute(): ?array
    {
        if (is_null($this->config_from)) {
            return $this->file_denylist;
        }

        return $this->configFrom->file_denylist;
    }

    /**
     * Gets all servers associated with this egg.
     */
    public function servers(): HasMany
    {
        return $this->hasMany(Server::class, 'egg_id');
    }

    /**
     * Gets all variables associated with this egg.
     */
    public function variables(): HasMany
    {
        return $this->hasMany(EggVariable::class, 'egg_id');
    }

    /**
     * Get the parent egg from which to copy scripts.
     */
    public function scriptFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'copy_script_from');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'config_from');
    }

    /**
     * Get the parent egg from which to copy configuration settings.
     */
    public function configFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'config_from');
    }

    public function getKebabName(): string
    {
        return str($this->name)->kebab()->lower()->trim()->split('/[^\w\-]/')->join('');
    }
}
