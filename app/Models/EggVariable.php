<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EggVariable extends Model
{
    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'egg_variable';

    /**
     * Reserved environment variable names.
     */
    public const RESERVED_ENV_NAMES = 'SERVER_MEMORY,SERVER_IP,ENV,HOME,USER,STARTUP,SERVER_UUID,UUID';

    /**
     * The table associated with the model.
     */
    protected $table = 'egg_variables';

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static array $validationRules = [
        'egg_id' => 'exists:eggs,id',
        'sort' => 'nullable',
        'name' => 'required|string|between:1,191',
        'description' => 'string',
        'env_variable' => 'required|alphaDash|between:1,191|notIn:' . self::RESERVED_ENV_NAMES,
        'default_value' => 'string',
        'user_viewable' => 'boolean',
        'user_editable' => 'boolean',
        'rules' => 'string',
    ];

    protected $attributes = [
        'user_editable' => 0,
        'user_viewable' => 0,
    ];

    protected function casts(): array
    {
        return [
            'egg_id' => 'integer',
            'user_viewable' => 'bool',
            'user_editable' => 'bool',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    public function getRequiredAttribute(): bool
    {
        return in_array('required', explode('|', $this->rules));
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
