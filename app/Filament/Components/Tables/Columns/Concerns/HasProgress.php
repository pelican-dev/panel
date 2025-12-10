<?php

namespace App\Filament\Components\Tables\Columns\Concerns;

use Closure;
use Filament\Support\Colors\Color;

/**
 * Trait extracted for progress-related shared functionality between columns.
 *
 * @method mixed evaluate(mixed $value, array<string,mixed> $context = [])
 */
trait HasProgress
{
    protected float|Closure|null $warningThresholdPercent = null;

    protected float|Closure|null $dangerThresholdPercent = null;

    /**
     * @var string|array<int|string,string>|Closure|Color|null
     */
    protected string|array|Closure|Color|null $dangerColor = null;

    /**
     * @var string|array<int|string,string>|Closure|Color|null
     */
    protected string|array|Closure|Color|null $warningColor = null;

    /**
     * @var string|array<int|string,string>|Closure|Color|null
     */
    protected string|array|Closure|Color|null $color = null;

    public function warningThresholdPercent(float|Closure $value): static
    {
        $this->warningThresholdPercent = $value;

        return $this;
    }

    public function getWarningThresholdPercent(): ?float
    {
        return $this->evaluate($this->warningThresholdPercent);
    }

    public function dangerThresholdPercent(float|Closure $value): static
    {
        $this->dangerThresholdPercent = $value;

        return $this;
    }

    public function getDangerThresholdPercent(): ?float
    {
        return $this->evaluate($this->dangerThresholdPercent);
    }

    /**
     * @param  string|array<int|string,string>|Closure|Color  $color
     */
    public function dangerColor(string|array|Closure|Color $color): static
    {
        $this->dangerColor = $color;

        return $this;
    }

    /**
     * @return string|array<int|string,string>|Color|null
     */
    public function getDangerColor(): string|array|Color|null
    {
        return $this->evaluate($this->dangerColor);
    }

    /**
     * @param  string|array<int|string,string>|Closure|Color  $color
     */
    public function warningColor(string|array|Closure|Color $color): static
    {
        $this->warningColor = $color;

        return $this;
    }

    /**
     * @return string|array<int|string,string>|Color|null
     */
    public function getWarningColor(): string|array|Color|null
    {
        return $this->evaluate($this->warningColor);
    }

    /**
     * @param  string|array<int|string,string>|Closure|Color  $color
     */
    public function color(string|array|Closure|Color $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string|array<int|string,string>|Color|null
     */
    public function getColor(): string|array|Color|null
    {
        return $this->evaluate($this->color);
    }

    /**
     * Resolve a progress color for a given status string ('danger','warning','success').
     *
     * @return string|array<int|string,string>|Color
     */
    public function getProgressColorForStatus(string $status): string|array|Color
    {
        $color = match ($status) {
            'danger' => $this->getDangerColor(),
            'warning' => $this->getWarningColor(),
            'success' => $this->getColor(),
            default => $this->getColor(),
        };

        if ($color === null) {
            $color = $this->getColor();
        }

        if ($color === null) {
            return 'gray';
        }

        return $color;
    }
}
