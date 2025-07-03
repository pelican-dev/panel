<?php

namespace App\Services\Ssh;

use App\Models\User;
use App\Models\UserSSHKey;
use Exception;
use phpseclib3\Crypt\DSA;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Exception\NoKeyLoadedException;

class KeyCreationService
{
    /**
     * @throws Exception
     */
    public function handle(User $user, string $name, string $publicKey): UserSSHKey
    {
        try {
            $key = PublicKeyLoader::loadPublicKey($publicKey);
        } catch (NoKeyLoadedException) {
            throw new Exception('The public key provided is not valid');
        }

        if ($key instanceof DSA) {
            throw new Exception('DSA keys are not supported');
        }

        if ($key instanceof RSA && $key->getLength() < 2048) {
            throw new Exception('RSA keys must be at least 2048 bytes in length');
        }

        $fingerprint = $key->getFingerprint('sha256');
        if ($user->sshKeys()->where('fingerprint', $fingerprint)->exists()) {
            throw new Exception('The public key provided already exists on your account');
        }

        /** @var UserSSHKey $sshKey */
        $sshKey = $user->sshKeys()->create([
            'name' => $name,
            'public_key' => $key->toString('PKCS8'),
            'fingerprint' => $fingerprint,
        ]);

        return $sshKey;
    }
}
