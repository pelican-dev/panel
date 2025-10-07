<?php

namespace App\Services\Nodes;

use App\Extensions\Lcobucci\JWT\Encoding\TimestampDates;
use App\Models\Node;
use App\Models\User;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;

class NodeJWTService
{
    /** @var array<array-key, mixed> */
    private array $claims = [];

    private ?User $user = null;

    private DateTimeImmutable $expiresAt;

    private ?string $subject = null;

    /**
     * Set the claims to include in this JWT.
     *
     * @param  array<array-key, mixed>  $claims
     */
    public function setClaims(array $claims): self
    {
        $this->claims = $claims;

        return $this;
    }

    /**
     * Attaches a user to the JWT being created and will automatically inject the
     * "user_uuid" key into the final claims array with the user's UUID.
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setExpiresAt(DateTimeImmutable $date): self
    {
        $this->expiresAt = $date;

        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Generate a new JWT for a given node.
     */
    public function handle(Node $node, ?string $identifiedBy, string $algo = 'sha256'): UnencryptedToken
    {
        $identifier = hash($algo, $identifiedBy);
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($node->daemon_token));

        $builder = $config->builder(new TimestampDates())
            ->issuedBy(config('app.url'))
            ->permittedFor($node->getConnectionAddress())
            ->identifiedBy($identifier)
            ->withHeader('jti', $identifier)
            ->issuedAt(CarbonImmutable::now())
            ->canOnlyBeUsedAfter(CarbonImmutable::now()->subMinutes(5));

        $builder = $builder->expiresAt($this->expiresAt);

        if (!empty($this->subject)) {
            $builder = $builder
                ->relatedTo($this->subject)
                ->withHeader('sub', $this->subject);
        }

        foreach ($this->claims as $key => $value) {
            $builder = $builder->withClaim($key, $value);
        }

        if (!is_null($this->user)) {
            $builder = $builder->withClaim('user_uuid', $this->user->uuid);
        }

        return $builder
            ->withClaim('unique_id', Str::random())
            ->getToken($config->signer(), $config->signingKey());
    }
}
