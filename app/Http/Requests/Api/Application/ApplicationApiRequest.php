<?php

namespace App\Http\Requests\Api\Application;

use Webmozart\Assert\Assert;
use App\Models\ApiKey;
use Laravel\Sanctum\TransientToken;
use Illuminate\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use App\Services\Acl\Api\AdminAcl;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\PanelException;

abstract class ApplicationApiRequest extends FormRequest
{
    /**
     * The resource that should be checked when performing the authorization
     * function for this request.
     */
    protected ?string $resource;

    /**
     * The permission level that a given API key should have for accessing
     * the defined $resource during the request cycle.
     */
    protected int $permission = AdminAcl::NONE;

    /**
     * Determine if the current user is authorized to perform
     * the requested action against the API.
     *
     * @throws \App\Exceptions\PanelException
     */
    public function authorize(): bool
    {
        if (is_null($this->resource)) {
            throw new PanelException('An ACL resource must be defined on API requests.');
        }

        /** @var TransientToken|ApiKey $token */
        $token = $this->user()->currentAccessToken();

        if ($token instanceof TransientToken) {
            return true;
        }

        if ($token->key_type === ApiKey::TYPE_ACCOUNT) {
            return true;
        }

        return AdminAcl::check($token, $this->resource, $this->permission);
    }

    /** @return array<string, string|string[]> */
    public function rules(): array
    {
        return [];
    }

    /**
     * Helper method allowing a developer to easily hook into this logic without having
     * to remember what the method name is called or where to use it. By default this is
     * a no-op.
     */
    public function withValidator(Validator $validator): void
    {
        // do nothing
    }

    /**
     * Returns the named route parameter and asserts that it is a real model that
     * exists in the database.
     *
     * @template T of \Illuminate\Database\Eloquent\Model
     *
     * @param  class-string<T>  $expect
     * @return T
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function parameter(string $key, string $expect)
    {
        $value = $this->route()->parameter($key);

        Assert::isInstanceOf($value, $expect);
        Assert::isInstanceOf($value, Model::class);
        Assert::true($value->exists);

        /* @var T $value */
        return $value;
    }
}
