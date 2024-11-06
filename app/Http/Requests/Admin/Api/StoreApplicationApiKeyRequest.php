<?php

namespace App\Http\Requests\Admin\Api;

use App\Models\ApiKey;
use App\Http\Requests\Admin\AdminFormRequest;

class StoreApplicationApiKeyRequest extends AdminFormRequest
{
    /**
     * @throws \ReflectionException
     * @throws \ReflectionException
     */
    public function rules(): array
    {
        $modelRules = ApiKey::getRules();

        $rules = [
            'memo' => $modelRules['memo'],
            'permissions' => $modelRules['permissions'],
        ];

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'memo' => 'Description',
        ];
    }

    public function getKeyPermissions(): array
    {
        $data = $this->validated();

        return array_keys($data['permissions']);
    }
}
