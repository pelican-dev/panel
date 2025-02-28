<?php

namespace App\Http\Requests\Api\Application\Users;

use App\Models\User;

class UpdateUserRequest extends StoreUserRequest
{
    /**
     * @param array<string, string|array<string>> |null $rules
     * @return array<string, string|array<string>>
     */
    public function rules(?array $rules = null): array
    {
        $user = $this->parameter('user', User::class);

        return parent::rules(User::getRulesForUpdate($user));
    }
}
