<?php

namespace App\Extensions\OAuth;

use App\Models\User;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Contracts\User as OAuthUser;
use SocialiteProviders\Manager\SocialiteWasCalled;

class OAuthService
{
    /** @var OAuthSchemaInterface[] */
    private array $schemas = [];

    /** @return OAuthSchemaInterface[] */
    public function getAll(): array
    {
        return $this->schemas;
    }

    public function get(string $id): ?OAuthSchemaInterface
    {
        return array_get($this->schemas, $id);
    }

    /** @return OAuthSchemaInterface[] */
    public function getEnabled(): array
    {
        return collect($this->schemas)
            ->filter(fn (OAuthSchemaInterface $schema) => $schema->isEnabled())
            ->all();
    }

    public function register(OAuthSchemaInterface $schema): void
    {
        if (array_key_exists($schema->getId(), $this->schemas)) {
            return;
        }

        config()->set('services.' . $schema->getId(), array_merge($schema->getServiceConfig(), ['redirect' => '/auth/oauth/callback/' . $schema->getId()]));

        if ($schema->getSocialiteProvider()) {
            Event::listen(fn (SocialiteWasCalled $event) => $event->extendSocialite($schema->getId(), $schema->getSocialiteProvider()));
        }

        $this->schemas[$schema->getId()] = $schema;
    }

    public function linkUser(User $user, OAuthSchemaInterface $schema, OAuthUser $oauthUser): User
    {
        $oauth = $user->oauth ?? [];
        $oauth[$schema->getId()] = $oauthUser->getId();

        $user->update(['oauth' => $oauth]);

        return $user->refresh();
    }

    public function unlinkUser(User $user, OAuthSchemaInterface $schema): User
    {
        $oauth = $user->oauth ?? [];
        if (!isset($oauth[$schema->getId()])) {
            return $user;
        }

        unset($oauth[$schema->getId()]);
        $user->update(['oauth' => $oauth]);

        return $user->refresh();
    }
}
