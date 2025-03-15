<?php

namespace App\Http\Requests\Api\Application\Users;

use App\Models\User;

class UpdateUserRequest extends StoreUserRequest
{
    /**
     * @param  array<array-key, string|string[]> |null  $rules
     * @return array<array-key, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        $user = $this->parameter('user', User::class);

        return parent::rules(User::getRulesForUpdate($user));
    }
}
