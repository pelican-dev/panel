<?php

namespace App\Http\Requests\Api\Client\Account;

use App\Exceptions\Http\Base\InvalidPasswordProvidedException;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Contracts\Hashing\Hasher;

class UpdateUsernameRequest extends ClientApiRequest
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
        throw_unless($hasher->check($this->input('password'), $this->user()->password), new InvalidPasswordProvidedException(trans('validation.internal.invalid_password')));

        return !$this->user()->is_managed_externally;
    }

    public function rules(): array
    {
        $rules = User::getRulesForUpdate($this->user());

        return ['username' => $rules['username']];
    }
}
