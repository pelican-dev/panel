<?php

namespace App\Extensions\Avatar\Providers;

use App\Extensions\Avatar\AvatarProvider;
use App\Models\User;

class GravatarProvider extends AvatarProvider
{
    public function getId(): string
    {
        return 'gravatar';
    }

    public function get(User $user): string
    {
        return 'https://gravatar.com/avatar/' . md5($user->email);
    }

    public static function register(): self
    {
        return new self();
    }
}
