<?php

namespace App\Transformers\Api\Client;

use App\Models\User;
use Illuminate\Support\Str;

class UserTransformer extends BaseClientTransformer
{
    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return User::RESOURCE_NAME;
    }

    /**
     * @param  User  $user
     *
     * {@inheritdoc}
     */
    public function transform($user): array
    {
        return [
            'uuid' => $user->uuid,
            'username' => $user->username,
            'email' => $user->email,
            'language' => $user->language,
            'image' => 'https://gravatar.com/avatar/' . md5(Str::lower($user->email)), // deprecated
            'admin' => $user->isRootAdmin(), // deprecated, use "root_admin"
            'root_admin' => $user->isRootAdmin(),
            '2fa_enabled' => filled($user->mfa_app_secret),
            'created_at' => $this->formatTimestamp($user->created_at),
            'updated_at' => $this->formatTimestamp($user->updated_at),
        ];
    }
}
