<?php

namespace App\Http\Requests\Api\Client\Account;

use Illuminate\Container\Container;
use Illuminate\Contracts\Hashing\Hasher;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Exceptions\Http\Base\InvalidPasswordProvidedException;

class UpdatePasswordRequest extends ClientApiRequest
{
    /**
     * @throws \App\Exceptions\Http\Base\InvalidPasswordProvidedException
     */
    public function authorize(): bool
    {
        if (!parent::authorize()) {
            return false;
        }

        $hasher = Container::getInstance()->make(Hasher::class);

        // Verify password matches when changing password or email.
        if (!$hasher->check($this->input('current_password'), $this->user()->password)) {
            throw new InvalidPasswordProvidedException(trans('validation.internal.invalid_password'));
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }
}
