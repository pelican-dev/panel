<?php

namespace App\Filament\Components\Tables\Columns;

use Closure;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\Column;

class ProgressBarColumn extends Column
{
    protected string $view = 'livewire.columns.progress-bar-column';

    protected int|Closure|null $maxValue = null;

    protected float|Closure|null $warningThresholdPercent = 0.7;

    protected float|Closure|null $dangerThresholdPercent = 0.9;

    protected string|array|Closure|Color|null $dangerColor = null;

    protected string|array|Closure|Color|null $warningColor = null;

    protected string|array|Closure|Color|null $color = null;

    protected string|Closure|null $helperLabel = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dangerColor = Color::Red[500];
        $this->warningColor = Color::Amber[500];
        $this->color = Color::Blue[500];

        $this->helperLabel = fn ($state) => $state !== null ? (string) $state : '0';
    }

    public function maxValue(int|Closure $value): static
    {
        $this->maxValue = $value;

        return $this;
    }

    public function getMaxValue(): ?int
    {
        return $this->evaluate($this->maxValue);
    }

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

    public function dangerColor(string|array|Closure $color): static
    {
        $this->dangerColor = $color;

        return $this;
    }

    public function getDangerColor(): string|array|null
    {
        return $this->normalizeColor($this->evaluate($this->dangerColor));
    }

    public function warningColor(string|array|Closure $color): static
    {
        $this->warningColor = $color;

        return $this;
    }

    public function getWarningColor(): string|array|null
    {
        return $this->normalizeColor($this->evaluate($this->warningColor));
    }

    public function color(string|array|Closure $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): string|array|null
    {
        return $this->normalizeColor($this->evaluate($this->color));
    }

    protected function normalizeColor(string|array|null $color): string|array|null
    {
        if ($color === null) {
            return null;
        }

        // Accept friendly aliases like 'danger', 'warning', 'primary' and map them
        if (is_string($color)) {
            $lower = strtolower(trim($color));

            $aliases = [
                'danger' => Color::Red,
                'warning' => Color::Amber,
                'primary' => Color::Blue,
            ];

            if (isset($aliases[$lower])) {
                $color = $aliases[$lower];
            }
        }

        if (is_array($color)) {
            $value = reset($color);

            if (is_string($value)) {
                return Color::convertToRgb($value);
            }

            return $color;
        }

        if (is_string($color) && str_starts_with($color, 'var(')) {
            return $color;
        }

        return Color::convertToRgb($color);
    }

    // Helper label setter/getter
    public function helperLabel(string|Closure $label): static
    {
        $this->helperLabel = $label;

        return $this;
    }

    public function getHelperLabel(int|float|null $currentValue): ?string
    {
        return $this->evaluate($this->helperLabel, [
            'state' => $currentValue,
            'percentage' => $this->getProgressPercentage(),
        ]);
    }

    public function getProgressPercentage(): float
    {
        $currentValue = $this->getState();
        $maxValue = $this->getMaxValue();

        if ($currentValue === null || $maxValue === null || $maxValue <= 0) {
            return 0;
        }

        return min(100, max(0, ($currentValue / $maxValue) * 100));
    }

    public function getProgressStatus(): string
    {
        $percentage = $this->getProgressPercentage();

        $dangerPercent = $this->getDangerThresholdPercent();
        $warningPercent = $this->getWarningThresholdPercent();

        $dangerThreshold = ($dangerPercent !== null ? $dangerPercent : 0.9) * 100;
        $warningThreshold = ($warningPercent !== null ? $warningPercent : 0.7) * 100;

        if ($percentage >= $dangerThreshold) {
            return 'danger';
        }

        if ($percentage >= $warningThreshold) {
            return 'warning';
        }

        return 'success';
    }

    public function getProgressLabel(): string
    {
        $currentValue = $this->getState();

        $label = $this->getHelperLabel($currentValue);

        if ($label !== null && $label !== '') {
            return $label;
        }

        return (string) round($this->getProgressPercentage()) . '%';
    }

    public function getProgressColor(): string|array
    {
        $status = $this->getProgressStatus();

        return match ($status) {
            'danger' => $this->getDangerColor(),
            'warning' => $this->getWarningColor(),
            'success' => $this->getColor(),
        };
    }
}
