<?php

namespace App\Filament\Components\Tables\Columns;

use App\Filament\Components\Tables\Columns\Concerns\HasProgress;
use Closure;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Columns\Column;

class ProgressBarColumn extends Column
{
    use HasProgress;

    protected string $view = 'livewire.columns.progress-bar-column';

    // Accept int or float for max values
    protected int|float|Closure|null $maxValue = null;

    protected string|Closure|null $helperLabel = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dangerColor = FilamentColor::getColor('danger');
        $this->warningColor = FilamentColor::getColor('warning');
        $this->color = FilamentColor::getColor('primary');

        $this->helperLabel = fn ($state) => $state !== null ? (string) $state : '0';
    }

    public function maxValue(int|float|Closure $value): static
    {
        $this->maxValue = $value;

        return $this;
    }

    public function getMaxValue(): ?float
    {
        return $this->evaluate($this->maxValue);
    }

    public function helperLabel(string|Closure $label): static
    {
        $this->helperLabel = $label;

        return $this;
    }

    public function getHelperLabel(mixed $currentValue = null): string
    {
        $result = $this->evaluate($this->helperLabel, [
            'state' => $currentValue,
            'percentage' => $this->getProgressPercentage(),
        ]);

        return $result !== null ? (string) $result : '';
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

        if ($label !== '') {
            return $label;
        }

        return sprintf('%d%%', (int) round($this->getProgressPercentage()));
    }

    /**
     * Return the resolved progress color. Always returns a non-null string or array.
     *
     * @return string|array<int|string,string>
     */
    public function getProgressColor(): string|array
    {
        $status = $this->getProgressStatus();

        $color = match ($status) {
            'danger' => $this->getDangerColor(),
            'warning' => $this->getWarningColor(),
            'success' => $this->getColor(),
            default => $this->getColor(),
        };

        // Normalize nullable branches to a non-null value. Prefer the resolved color, then fallback
        // to the primary color, then to a safe string 'gray'. This guarantees the return type is
        // string|array and avoids phpstan complaining about nullable returns in the match.
        if ($color === null) {
            $color = $this->getColor();
        }

        if ($color === null) {
            return 'gray';
        }

        return $color;
    }
}
