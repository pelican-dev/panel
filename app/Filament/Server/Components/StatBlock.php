<?php

namespace App\Filament\Server\Components;

use Closure;
use Filament\Support\Concerns\Macroable;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;

class StatBlock extends Stat
{
    use Macroable;

    /**
     * @var string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    protected string|array|null $color = null;

    protected string|Htmlable|null $description = null;

    /**
     * @var string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    protected string|array|null $descriptionColor = null;

    /**
     * @var array<string, scalar>
     */
    protected array $extraAttributes = [];

    protected ?string $id = null;

    protected string|Htmlable $label;

    /**
     * @var scalar | Htmlable | Closure
     */
    protected $value;

    /**
     * @param  string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null  $color
     */
    public function color(string|array|null $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function description(string|Htmlable|null $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param  string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null  $color
     */
    public function descriptionColor(string|array|null $color): static
    {
        $this->descriptionColor = $color;

        return $this;
    }

    /**
     * @param  array<string, scalar>  $attributes
     */
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

    /**
     * @param  scalar | Htmlable | Closure  $value
     */
    public function value($value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    public function getColor(): string|array|null
    {
        return $this->color;
    }

    public function getDescription(): string|Htmlable|null
    {
        return $this->description;
    }

    /**
     * @return string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    public function getDescriptionColor(): string|array|null
    {
        return $this->descriptionColor ?? $this->color;
    }

    /**
     * @return array<string, scalar>
     */
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

    /**
     * @return scalar | Htmlable | Closure
     */
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
