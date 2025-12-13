<?php

namespace App\Http\Requests\Api\Application\Users;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\User;
use App\Services\Acl\Api\AdminAcl;

class StoreUserRequest extends ApplicationApiRequest
{
    protected ?string $resource = User::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /**
     * @param  array<array-key, string|string[]> |null  $rules
     * @return array<array-key, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        $rules = $rules ?? User::getRules();

        return collect($rules)->only([
            'external_id',
            'is_managed_externally',
            'email',
            'username',
            'password',
            'language',
            'timezone',
        ])->toArray();
    }

    /**
     * Rename some fields to be more user-friendly.
     *
     * @return array<array-key, string>
     */
    public function attributes(): array
    {
        return [
            'external_id' => 'Third Party Identifier',
            'is_managed_externally' => 'Is managed by Third Party?',
        ];
    }
}
