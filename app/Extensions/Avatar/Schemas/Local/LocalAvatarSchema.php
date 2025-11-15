<?php

namespace App\Extensions\Avatar\Schemas\Local;

use App\Extensions\Avatar\AvatarSchemaInterface;
use App\Models\User;

class LocalAvatarSchema implements AvatarSchemaInterface
{
    public function getId(): string
    {
        return 'local';
    }

    public function getName(): string
    {
        return 'Local Avatar';
    }

    public function get(User $user): string
    {
        $provider = new LocalAvatarProvider();

        return $provider->get($user);
    }
}
