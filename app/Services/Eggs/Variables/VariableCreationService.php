<?php

namespace App\Services\Eggs\Variables;

use App\Exceptions\Model\DataValidationException;
use App\Exceptions\Service\Egg\Variable\BadValidationRuleException;
use App\Exceptions\Service\Egg\Variable\ReservedVariableNameException;
use App\Models\EggVariable;
use App\Traits\Services\ValidatesValidationRules;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class VariableCreationService
{
    use ValidatesValidationRules;

    /**
     * VariableCreationService constructor.
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
     * Create a new variable for a given Egg.
     *
     * @param array{
     *     name?: string,
     *     description?: string,
     *     env_variable?: string,
     *     default_value?: string,
     *     rules?: string|string[],
     * } $data
     *
     * @throws DataValidationException
     * @throws BadValidationRuleException
     * @throws ReservedVariableNameException
     */
    public function handle(int $egg, array $data): EggVariable
    {
        if (in_array(strtoupper(array_get($data, 'env_variable')), EggVariable::RESERVED_ENV_NAMES)) {
            throw new ReservedVariableNameException(sprintf('Cannot use the protected name %s for this environment variable.', array_get($data, 'env_variable')));
        }

        if (!empty($data['rules'] ?? [])) {
            $this->validateRules($data['rules']);
        }

        $options = array_get($data, 'options') ?? [];

        /** @var EggVariable $eggVariable */
        $eggVariable = EggVariable::query()->create([
            'egg_id' => $egg,
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'env_variable' => $data['env_variable'] ?? '',
            'default_value' => $data['default_value'] ?? '',
            'user_viewable' => in_array('user_viewable', $options),
            'user_editable' => in_array('user_editable', $options),
            'rules' => $data['rules'] ?? [],
        ]);

        return $eggVariable;
    }
}
