<?php

namespace App\Http\Requests\Api\Application\Users;

use App\Models\User;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class StoreUserRequest extends ApplicationApiRequest
{
    protected ?string $resource = User::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /**
     * Return the validation rules for this request.
     */
    public function rules(?array $rules = null): array
    {
        $rules = $rules ?? User::getRules();

        $response = collect($rules)->only([
            'external_id',
            'email',
            'username',
            'password',
            'language',
            'timezone',
        ])->toArray();

        $response['first_name'] = $rules['name_first'];
        $response['last_name'] = $rules['name_last'];

        return $response;
    }

    public function validated($key = null, $default = null): array
    {
        $data = parent::validated();

        $data['name_first'] = $data['first_name'];
        $data['name_last'] = $data['last_name'];

        unset($data['first_name'], $data['last_name']);

        return $data;
    }

    /**
     * Rename some fields to be more user friendly.
     */
    public function attributes(): array
    {
        return [
            'external_id' => 'Third Party Identifier',
            'name_first' => 'First Name',
            'name_last' => 'Last Name',
        ];
    }
}
