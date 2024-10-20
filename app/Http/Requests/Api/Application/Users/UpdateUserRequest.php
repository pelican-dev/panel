<?php

namespace App\Http\Requests\Api\Application\Users;

use App\Models\User;

class UpdateUserRequest extends StoreUserRequest
{
    /**
     * Return the validation rules for this request.
     */
    public function rules(?array $rules = null): array
    {
        $user = $this->parameter('user', User::class);

        return parent::rules(User::getRulesForUpdate($user));
    }
}
