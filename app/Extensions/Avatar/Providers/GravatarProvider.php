<?php

namespace App\Extensions\Avatar\Providers;

use App\Extensions\Avatar\AvatarProvider;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class GravatarProvider extends AvatarProvider
{
    public function getId(): string
    {
        return 'gravatar';
    }

    public function get(Model|Authenticatable $record): string
    {
        /** @var User $record */
        return 'https://gravatar.com/avatar/' . md5($record->email);
    }

    public static function register(): self
    {
        return new self();
    }
}
