<?php

namespace App\Filament\Server\Components;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Support\Htmlable;

class StatBlock extends Stat
{
    protected string|\Closure|Htmlable|null $label;

    protected string $view = 'filament.components.server-data-block';

    protected $value;

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function value($value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getValue(): mixed
    {
        return value($this->value);
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }
}
