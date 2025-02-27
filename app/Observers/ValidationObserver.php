<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Exceptions\Model\DataValidationException;
use App\Contracts\Validatable as HasValidationContract;

class ValidationObserver
{
    public function saving(Model&HasValidationContract $model): void
    {
        try {
            $model->validate();
        } catch (ValidationException $exception) {
            throw new DataValidationException($exception->validator, $model);
        }
    }
}
