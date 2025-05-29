<?php

namespace App\Extensions\Captcha;

use App\Extensions\Captcha\Schemas\CaptchaSchemaInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CaptchaProvider
{
    /** @var array<string, CaptchaSchemaInterface> */
    private array $schemas = [];

    /**
     * @return array<string, CaptchaSchemaInterface> | CaptchaSchemaInterface
     */
    public function get(?string $id = null): array|CaptchaSchemaInterface
    {
        return $id ? $this->schemas[$id] : $this->schemas;
    }

    public function register(CaptchaSchemaInterface $schema): void
    {
        if (array_key_exists($schema->getId(), $this->schemas)) {
            return;
        }

        config()->set('captcha.' . Str::lower($schema->getId()), $schema->getConfig());
        $this->schemas[$schema->getId()] = $schema;
    }

    /** @return Collection<CaptchaSchemaInterface> */
    public function getActiveSchemas(): Collection
    {
        return collect($this->schemas)
            ->filter(fn (CaptchaSchemaInterface $schema) => $schema->isEnabled());
    }

    public function getActiveSchema(): ?CaptchaSchemaInterface
    {
        return $this->getActiveSchemas()->first();
    }
}
