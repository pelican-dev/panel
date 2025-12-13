<?php

namespace App\Http\Requests\Api\Client\Account;

use App\Exceptions\Http\Base\InvalidPasswordProvidedException;
use App\Http\Requests\Api\Client\ClientApiRequest;
use Illuminate\Container\Container;
use Illuminate\Contracts\Hashing\Hasher;

class UpdatePasswordRequest extends ClientApiRequest
{
    /**
     * @throws InvalidPasswordProvidedException
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

        return !$this->user()->is_managed_externally;
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }
}
