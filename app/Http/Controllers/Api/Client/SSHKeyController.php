<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\UserSSHKey;
use Illuminate\Http\JsonResponse;
use App\Facades\Activity;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Transformers\Api\Client\UserSSHKeyTransformer;
use App\Http\Requests\Api\Client\Account\StoreSSHKeyRequest;

class SSHKeyController extends ClientApiController
{
    /**
     * List ssh keys
     *
     * Returns all the SSH keys that have been configured for the logged-in user account.
     *
     * @return array<array-key, mixed>
     */
    public function index(ClientApiRequest $request): array
    {
        return $this->fractal->collection($request->user()->sshKeys)
            ->transformWith($this->getTransformer(UserSSHKeyTransformer::class))
            ->toArray();
    }

    /**
     * Create ssh keys
     *
     * Stores a new SSH key for the authenticated user's account.
     *
     * @return array<array-key, mixed>
     */
    public function store(StoreSSHKeyRequest $request): array
    {
        $model = $request->user()->sshKeys()->create([
            'name' => $request->input('name'),
            'public_key' => $request->getPublicKey(),
            'fingerprint' => $request->getKeyFingerprint(),
        ]);

        Activity::event('user:ssh-key.create')
            ->subject($model)
            ->property('fingerprint', $request->getKeyFingerprint())
            ->log();

        return $this->fractal->item($model)
            ->transformWith($this->getTransformer(UserSSHKeyTransformer::class))
            ->toArray();
    }

    /**
     * Delete ssh keys
     *
     * Deletes an SSH key from the user's account.
     */
    public function delete(ClientApiRequest $request): JsonResponse
    {
        $request->validate(['fingerprint' => ['required', 'string']]);

        /** @var ?UserSSHKey $key */
        $key = $request->user()->sshKeys()
            ->where('fingerprint', $request->input('fingerprint'))
            ->first();

        if (!is_null($key)) {
            $key->delete();

            Activity::event('user:ssh-key.delete')
                ->subject($key)
                ->property('fingerprint', $key->fingerprint)
                ->log();
        }

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
