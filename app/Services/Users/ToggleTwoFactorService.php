<?php

namespace App\Services\Users;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Database\ConnectionInterface;
use App\Exceptions\Service\User\TwoFactorAuthenticationTokenInvalid;

class ToggleTwoFactorService
{
    /**
     * ToggleTwoFactorService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private Google2FA $google2FA,
    ) {}

    /**
     * Toggle 2FA on an account only if the token provided is valid.
     *
     * @return string[]
     *
     * @throws \Throwable
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     * @throws \App\Exceptions\Service\User\TwoFactorAuthenticationTokenInvalid
     */
    public function handle(User $user, string $token, ?bool $toggleState = null): array
    {
        $isValidToken = $this->google2FA->verifyKey($user->totp_secret, $token, config()->get('panel.auth.2fa.window'));

        if (!$isValidToken) {
            throw new TwoFactorAuthenticationTokenInvalid();
        }

        return $this->connection->transaction(function () use ($user, $toggleState) {
            // Now that we're enabling 2FA on the account, generate 10 recovery tokens for the account
            // and store them hashed in the database. We'll return them to the caller so that the user
            // can see and save them.
            //
            // If a user is unable to login with a 2FA token they can provide one of these backup codes
            // which will then be marked as deleted from the database and will also bypass 2FA protections
            // on their account.
            $tokens = [];
            if ((!$toggleState && !$user->use_totp) || $toggleState) {
                $user->recoveryTokens()->delete();
                for ($i = 0; $i < 10; $i++) {
                    $token = str_random(10);
                    $user->recoveryTokens()->forceCreate([
                        'token' => password_hash($token, PASSWORD_DEFAULT),
                    ]);
                    $tokens[] = $token;
                }
            }

            $user->totp_authenticated_at = now();
            $user->use_totp = (is_null($toggleState) ? !$user->use_totp : $toggleState);
            $user->save();

            return $tokens;
        });
    }
}
