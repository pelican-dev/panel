<?php

namespace App\Traits\Services;

use App\Exceptions\Service\Egg\Variable\BadValidationRuleException;
use BadMethodCallException;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

trait ValidatesValidationRules
{
    abstract protected function getValidator(): ValidationFactory;

    /**
     * Validate that the rules being provided are valid and can be resolved.
     *
     * @param  string[]|string|ValidationRule[]  $rules
     *
     * @throws BadValidationRuleException
     */
    public function validateRules(array|string $rules): void
    {
        try {
            $this->getValidator()->make(['__TEST' => 'test'], ['__TEST' => $rules])->fails();
        } catch (BadMethodCallException $exception) {
            $matches = [];
            if (preg_match('/Method \[(.+)\] does not exist\./', $exception->getMessage(), $matches)) {
                throw new BadValidationRuleException(trans('exceptions.variables.bad_validation_rule', ['rule' => Str::snake(str_replace('validate', '', array_get($matches, 1, 'unknownRule')))]), $exception);
            }

            throw $exception;
        }
    }
}
