<?php

namespace App\Services\Users;

use App\Models\User;

class TwoFactorSetupService
{
    public const VALID_BASE32_CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a 2FA token and store it in the database before returning the
     * QR code URL. This URL will need to be attached to a QR generating service in
     * order to function.
     *
     * @return array{image_url_data: string, secret: string}
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function handle(User $user): array
    {
        $secret = '';
        try {
            for ($i = 0; $i < config('panel.auth.2fa.bytes', 16); $i++) {
                $secret .= substr(self::VALID_BASE32_CHARACTERS, random_int(0, 31), 1);
            }
        } catch (\Exception $exception) {
            throw new \RuntimeException($exception->getMessage(), 0, $exception);
        }

        $user->totp_secret = $secret;
        $user->save();

        $company = urlencode(preg_replace('/\s/', '', config('app.name')));

        return [
            'image_url_data' => sprintf(
                'otpauth://totp/%1$s:%2$s?secret=%3$s&issuer=%1$s',
                rawurlencode($company),
                rawurlencode($user->email),
                rawurlencode($secret),
            ),
            'secret' => $secret,
        ];
    }
}
