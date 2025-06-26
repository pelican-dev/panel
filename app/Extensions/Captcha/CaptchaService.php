<?php

namespace App\Extensions\Captcha;

use App\Extensions\Captcha\Schemas\CaptchaSchemaInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CaptchaService
{
    /** @var array<string, CaptchaSchemaInterface> */
    private array $schemas = [];

    /**
     * @return CaptchaSchemaInterface[]
     */
    public function getAll(): array
    {
        return $this->schemas;
    }

    public function get(string $id): ?CaptchaSchemaInterface
    {
        return array_get($this->schemas, $id);
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
