<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $egg_id
 * @property null $sort
 * @property string $name
 * @property string $description
 * @property string $env_variable
 * @property string $default_value
 * @property bool $user_viewable
 * @property bool $user_editable
 * @property string[] $rules
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property bool $required
 * @property Egg $egg
 * @property ServerVariable $serverVariable
 *
 * The "server_value" variable is only present on the object if you've loaded this model
 * using the server relationship.
 * @property string|null $server_value
 */
class EggVariable extends Model implements Validatable
{
    use HasFactory;
    use HasValidation { getRules as getValidationRules; }

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'egg_variable';

    /**
     * Reserved environment variable names.
     */
    public const RESERVED_ENV_NAMES = ['P_SERVER_UUID', 'P_SERVER_ALLOCATION_LIMIT', 'SERVER_MEMORY', 'SERVER_IP', 'SERVER_PORT', 'ENV', 'HOME', 'USER', 'STARTUP', 'MODIFIED_STARTUP', 'SERVER_UUID', 'UUID', 'INTERNAL_IP', 'HOSTNAME', 'TERM', 'LANG', 'PWD', 'TZ', 'TIMEZONE'];

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<string, string[]> */
    public static array $validationRules = [
        'egg_id' => ['exists:eggs,id'],
        'sort' => ['nullable'],
        'name' => ['required', 'string', 'between:1,255'],
        'description' => ['string'],
        'default_value' => ['string'],
        'user_viewable' => ['boolean'],
        'user_editable' => ['boolean'],
        'rules' => ['array'],
        'rules.*' => ['string'],
    ];

    /**
     * Implement language verification by overriding Eloquence's gather rules function.
     *
     * @return array<string|string[]>
     */
    public static function getRules(): array
    {
        $rules = self::getValidationRules();

        $rules['env_variable'] = ['required', 'alphaDash', 'between:1,255', 'notIn:' . implode(',', EggVariable::RESERVED_ENV_NAMES)];

        return $rules;
    }

    protected $attributes = [
        'user_editable' => 0,
        'user_viewable' => 0,
        'rules' => '[]',
    ];

    protected function casts(): array
    {
        return [
            'egg_id' => 'integer',
            'user_viewable' => 'bool',
            'user_editable' => 'bool',
            'rules' => 'array',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    public function getRequiredAttribute(): bool
    {
        return in_array('required', $this->rules);
    }

    public function egg(): HasOne
    {
        return $this->hasOne(Egg::class);
    }

    /**
     * Return server variables associated with this variable.
     */
    public function serverVariable(): HasMany
    {
        return $this->hasMany(ServerVariable::class, 'variable_id');
    }
}
