<?php

namespace App\Models\Objects;

class DeploymentObject
{
    private bool $dedicated = false;

    /** @var string[] */
    private array $tags = [];

    /** @var array<int|string> */
    private array $ports = [];

    public function isDedicated(): bool
    {
        return $this->dedicated;
    }

    public function setDedicated(bool $dedicated): self
    {
        $this->dedicated = $dedicated;

        return $this;
    }

    /** @return array<int|string> */
    public function getPorts(): array
    {
        return $this->ports;
    }

    /** @param array<int|string> $ports */
    public function setPorts(array $ports): self
    {
        $this->ports = $ports;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param  string[]  $tags
     * @return $this
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }
}
