<?php

namespace App\Models\Objects;

use App\Models\Node;

class DeploymentObject
{
    private bool $dedicated = false;

    private array $tags = [];

    private array $ports = [];

    private Node $node;

    public function isDedicated(): bool
    {
        return $this->dedicated;
    }

    public function setDedicated(bool $dedicated): self
    {
        $this->dedicated = $dedicated;

        return $this;
    }

    public function getPorts(): array
    {
        return $this->ports;
    }

    public function setPorts(array $ports): self
    {
        $this->ports = $ports;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function setNode(Node $node): self
    {
        $this->node = $node;
    }
}
