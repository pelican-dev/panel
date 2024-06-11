<?php

namespace App\Exceptions\Service\User;

use App\Exceptions\DisplayException;

class TwoFactorAuthenticationTokenInvalid extends DisplayException
{
    public string $title = 'Invalid 2FA Code';
    public string $icon = 'tabler-2fa';

    public function __construct()
    {
        parent::__construct('The provided two-factor authentication token was not valid.');
    }
}
