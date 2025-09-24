<?php

namespace App\Exceptions\Model;

use App\Exceptions\PanelException;
use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class DataValidationException extends PanelException implements HttpExceptionInterface, MessageProvider
{
    /**
     * DataValidationException constructor.
     */
    public function __construct(protected Validator $validator, protected Model $model)
    {
        $message = sprintf(
            'Could not save %s[%s]: failed to validate data: %s',
            get_class($model),
            $model->getKey(),
            $validator->errors()->toJson()
        );

        parent::__construct($message);
    }

    /**
     * Return the validator message bag.
     */
    public function getMessageBag(): MessageBag
    {
        return $this->validator->errors();
    }

    /**
     * Return the status code for this request.
     */
    public function getStatusCode(): int
    {
        return 500;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return [];
    }

    public function getValidator(): Validator
    {
        return $this->validator;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
