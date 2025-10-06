<?php

namespace App\Services\Eggs\Variables;

use App\Exceptions\DisplayException;
use App\Exceptions\Model\DataValidationException;
use App\Exceptions\Service\Egg\Variable\ReservedVariableNameException;
use App\Models\EggVariable;
use App\Traits\Services\ValidatesValidationRules;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class VariableUpdateService
{
    use ValidatesValidationRules;

    /**
     * VariableUpdateService constructor.
     */
    public function __construct(private ValidationFactory $validator) {}

    /**
     * Return the validation factory instance to be used by rule validation
     * checking in the trait.
     */
    protected function getValidator(): ValidationFactory
    {
        return $this->validator;
    }

    /**
     * Update a specific egg variable.
     *
     * @param array{
     *     env_variable?: string,
     *     rules?: string|string[],
     *     options?: string[],
     *     name?: string,
     *     description?: string,
     *     default_value?: string,
     * } $data
     *
     * @throws DisplayException
     * @throws DataValidationException
     * @throws ReservedVariableNameException
     */
    public function handle(EggVariable $variable, array $data): EggVariable
    {
        if (!is_null(array_get($data, 'env_variable'))) {
            if (in_array(strtoupper(array_get($data, 'env_variable')), EggVariable::RESERVED_ENV_NAMES)) {
                throw new ReservedVariableNameException(trans('exceptions.variables.reserved_name', ['name' => array_get($data, 'env_variable')]));
            }

            $search = EggVariable::query()
                ->where('env_variable', $data['env_variable'])
                ->where('egg_id', $variable->egg_id)
                ->whereNot('id', $variable->id)
                ->count();

            if ($search > 0) {
                throw new DisplayException(trans('exceptions.variables.env_not_unique', ['name' => array_get($data, 'env_variable')]));
            }
        }

        if (!empty($data['rules'] ?? [])) {
            $this->validateRules($data['rules']);
        }

        $options = array_get($data, 'options') ?? [];

        $variable->update([
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'env_variable' => $data['env_variable'] ?? '',
            'default_value' => $data['default_value'] ?? '',
            'user_viewable' => in_array('user_viewable', $options),
            'user_editable' => in_array('user_editable', $options),
            'rules' => $data['rules'] ?? [],
        ]);

        return $variable;
    }
}
