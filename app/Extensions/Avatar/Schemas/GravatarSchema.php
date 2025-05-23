<?php

namespace App\Extensions\Avatar\Schemas;

use App\Extensions\Avatar\AvatarSchemaInterface;
use App\Models\User;

class GravatarSchema implements AvatarSchemaInterface
{
    public function getId(): string
    {
        return 'gravatar';
    }

    public function getName(): string
    {
        return 'Gravatar';
    }

    public function get(User $user): string
    {
        return 'https://gravatar.com/avatar/' . md5($user->email);
    }
}
