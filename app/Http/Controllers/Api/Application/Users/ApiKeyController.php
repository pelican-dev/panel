<?php

namespace App\Http\Controllers\Api\Application\Users;

use App\Exceptions\DisplayException;
use App\Facades\Activity;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Users\DeleteUserApiKeyRequest;
use App\Http\Requests\Api\Application\Users\GetUserApiKeysRequest;
use App\Http\Requests\Api\Application\Users\StoreUserApiKeyRequest;
use App\Models\ApiKey;
use App\Models\User;
use App\Transformers\Api\Application\ApiKeyTransformer;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;

#[Group('User', weight: 0)]
class ApiKeyController extends ApplicationApiController
{
    /**
     * List api keys
     *
     * Returns all client API keys that exist for the given user.
     *
     * @return array<array-key, mixed>
     */
    public function index(GetUserApiKeysRequest $request, User $user): array
    {
        return $this->fractal->collection($user->apiKeys)
            ->transformWith($this->getTransformer(ApiKeyTransformer::class))
            ->toArray();
    }

    /**
     * Create api key
     *
     * Store a new client API key for the given user.
     *
     * @return array<array-key, mixed>
     *
     * @throws DisplayException
     */
    public function store(StoreUserApiKeyRequest $request, User $user): array
    {
        throw_if($user->apiKeys()->count() >= config('panel.api.key_limit'), new DisplayException('You have reached the account limit for number of API keys.'));

        $token = $user->createToken(
            $request->input('description'),
            $request->input('allowed_ips')
        );

        Activity::event('user:api-key.create')
            ->subject($token->accessToken, $user)
            ->property('identifier', $token->accessToken->identifier)
            ->log();

        return $this->fractal->item($token->accessToken)
            ->transformWith($this->getTransformer(ApiKeyTransformer::class))
            ->addMeta(['secret_token' => $token->plainTextToken])
            ->toArray();
    }

    /**
     * Delete api key
     *
     * Deletes a client API key owned by the given user.
     */
    public function delete(DeleteUserApiKeyRequest $request, User $user, string $identifier): JsonResponse
    {
        /** @var ApiKey $key */
        $key = $user->apiKeys()
            ->where('identifier', $identifier)
            ->firstOrFail();

        Activity::event('user:api-key.delete')
            ->subject($key, $user)
            ->property('identifier', $key->identifier)
            ->log();

        $key->delete();

        return new JsonResponse((object) []);
    }
}
