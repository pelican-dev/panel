<?php

namespace App\Data;

use App\Models\User;
use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\MapOutputName;

class UserData extends Data
{
    public function __construct(
        public string $uuid,
        public string $username,
        public string $email,
        public string $language,
        public string $image,
        public bool $admin,
        public bool $root_admin,
        #[MapOutputName('2fa_enabled')]
        public bool $two_fa_enabled,
        public string $created_at,
        public string $updated_at,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            uuid: $user->uuid,
            username: $user->username,
            email: $user->email,
            language: $user->language,
            image: 'https://gravatar.com/avatar/' . md5(Str::lower($user->email)),
            admin: $user->isRootAdmin(),
            root_admin: $user->isRootAdmin(),
            two_fa_enabled: filled($user->mfa_app_secret),
            created_at: $user->created_at->setTimezone('UTC')->toAtomString(),
            updated_at: $user->updated_at->setTimezone('UTC')->toAtomString(),
        );
    }

    public function getResourceName(): string
    {
        return static::getResourceNameStatic();
    }

    public static function getResourceNameStatic(): string
    {
        return User::RESOURCE_NAME;
    }
}
