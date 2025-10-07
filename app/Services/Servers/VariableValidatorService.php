<?php

namespace App\Services\Servers;

use App\Models\EggVariable;
use App\Models\User;
use App\Traits\Services\HasUserLevels;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class VariableValidatorService
{
    use HasUserLevels;

    public function __construct(private readonly ValidationFactory $validator) {}

    /**
     * Validate  passed data against the given egg variables.
     *
     * @param  array<array-key, ?string>  $fields
     *
     * @throws ValidationException
     */
    public function handle(int $egg, array $fields = []): Collection
    {
        $query = EggVariable::query()->where('egg_id', $egg);
        if (!$this->isUserLevel(User::USER_LEVEL_ADMIN)) {
            // Don't attempt to validate variables if they aren't user editable,
            // and we're not running this at an admin level.
            $query = $query->where('user_editable', true)->where('user_viewable', true);
        }

        /** @var EggVariable[] $variables */
        $variables = $query->get();

        $data = $rules = $customAttributes = [];
        foreach ($variables as $variable) {
            $data['environment'][$variable->env_variable] = $fields[$variable->env_variable] ?? $variable->default_value;
            $rules['environment.' . $variable->env_variable] = $variable->rules;
            $customAttributes['environment.' . $variable->env_variable] = trans('validation.internal.variable_value', ['env' => $variable->name]);
        }

        $validator = $this->validator->make($data, $rules, [], $customAttributes);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return Collection::make($variables)->map(function ($item) use ($fields) {
            return (object) [
                'id' => $item->id,
                'key' => $item->env_variable,
                'value' => $fields[$item->env_variable] ?? null,
            ];
        });
    }
}
