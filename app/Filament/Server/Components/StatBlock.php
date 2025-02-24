<?php

namespace App\Filament\Server\Components;

use Filament\Support\Concerns\Macroable;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;

class StatBlock extends Stat
{
    use Macroable;

    protected array $extraAttributes = [];

    protected ?string $id = null;

    protected string|Htmlable $label;

    protected $value;

    public function extraAttributes(array $attributes): static
    {
        $this->extraAttributes = $attributes;

        return $this;
    }

    public function label(string|Htmlable $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function value($value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getExtraAttributes(): array
    {
        return $this->extraAttributes;
    }

    public function getExtraAttributeBag(): ComponentAttributeBag
    {
        return new ComponentAttributeBag($this->getExtraAttributes());
    }

    public function getLabel(): string|Htmlable
    {
        return $this->label;
    }

    public function getId(): string
    {
        return $this->id ?? Str::slug($this->getLabel());
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
        return view('filament.components.server-data-block', $this->data());
    }
}
