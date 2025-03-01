<?php

namespace App\Filament\Server\Components;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class SmallStatBlock extends Stat
{
    protected string|Htmlable $label;

    protected $value;

    public function label(string|Htmlable $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function value($value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getLabel(): string|Htmlable
    {
        return $this->label;
    }

    public function getValue()
    {
        return value($this->value);
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    public function render(): View
    {
        return view('filament.components.server-small-data-block', $this->data());
    }
}
