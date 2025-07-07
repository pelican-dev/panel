<?php

namespace App\Extensions\Avatar;

use App\Models\User;

interface AvatarSchemaInterface
{
    public function getId(): string;

    public function getName(): string;

    public function get(User $user): ?string;
}
