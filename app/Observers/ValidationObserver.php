<?php

namespace App\Observers;

use App\Contracts\Validatable as HasValidationContract;
use App\Exceptions\Model\DataValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

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
