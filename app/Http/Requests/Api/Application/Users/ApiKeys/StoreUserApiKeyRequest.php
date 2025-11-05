<?php

namespace App\Http\Requests\Api\Application\Users\ApiKeys;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\ApiKey;
use App\Models\User;
use App\Services\Acl\Api\AdminAcl as Acl;
use Exception;
use Illuminate\Validation\Validator;
use IPTools\Range;

class StoreUserApiKeyRequest extends ApplicationApiRequest
{
    protected ?string $resource = User::RESOURCE_NAME;

    protected int $permission = Acl::WRITE;

    /** @return array<array-key, string|string[]> */
    public function rules(): array
    {
        $rules = ApiKey::getRules();

        return [
            'description' => $rules['memo'],
            'allowed_ips' => [...$rules['allowed_ips'], 'max:50'],
            'allowed_ips.*' => 'string',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (!is_array($ips = $this->input('allowed_ips'))) {
                return;
            }

            foreach ($ips as $index => $ip) {
                $valid = false;

                try {
                    $valid = Range::parse($ip)->valid();
                } catch (Exception $exception) {
                    if ($exception->getMessage() !== 'Invalid IP address format') {
                        throw $exception;
                    }
                } finally {
                    $validator->errors()->addIf(!$valid, "allowed_ips.{$index}", '"' . $ip . '" is not a valid IP address or CIDR range.');
                }
            }
        });
    }
}
