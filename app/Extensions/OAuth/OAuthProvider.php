<?php

namespace App\Extensions\OAuth;

use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;

class OAuthProvider
{
    /** @var OAuthSchemaInterface[] */
    private array $providers = [];

    /** @return OAuthSchemaInterface[] | OAuthSchemaInterface */
    public function get(?string $id = null): array|OAuthSchemaInterface
    {
        return $id ? $this->providers[$id] : $this->providers;
    }

    /** @return OAuthSchemaInterface[] */
    public function getEnabled(): array
    {
        return collect($this->providers)
            ->filter(fn (OAuthSchemaInterface $provider) => $provider->isEnabled())
            ->all();
    }

    public function register(OAuthSchemaInterface $provider): void
    {
        if (array_key_exists($provider->getId(), $this->providers)) {
            return;
        }

        config()->set('services.' . $provider->getId(), array_merge($provider->getServiceConfig(), ['redirect' => '/auth/oauth/callback/' . $provider->getId()]));

        if ($provider->getSocialiteProvider()) {
            Event::listen(fn (SocialiteWasCalled $event) => $event->extendSocialite($provider->getId(), $provider->getSocialiteProvider()));
        }

        $this->providers[$provider->getId()] = $provider;
    }
}
