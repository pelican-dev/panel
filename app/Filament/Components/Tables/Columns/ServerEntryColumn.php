<?php

namespace App\Filament\Components\Tables\Columns;

use Closure;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\Column;

class ServerEntryColumn extends Column
{
    protected string $view = 'livewire.columns.server-entry-column';

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->dangerColor = Color::Red[500];
        $this->warningColor = Color::Amber[500];
        $this->color = Color::Blue[500];
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

    /**
     * @param  string|array<int|string,string>|Closure  $color
     */
    public function dangerColor(string|array|Closure $color): static
    {
        $this->dangerColor = $color;

        return $this;
    }

    /**
     * @return string|array<int|string,string>|null
     */
    public function getDangerColor(): string|array|null
    {
        return $this->normalizeColor($this->evaluate($this->dangerColor));
    }

    /**
     * @param  string|array<int|string,string>|Closure  $color
     */
    public function warningColor(string|array|Closure $color): static
    {
        $this->warningColor = $color;

        return $this;
    }

    /**
     * @return string|array<int|string,string>|null
     */
    public function getWarningColor(): string|array|null
    {
        return $this->normalizeColor($this->evaluate($this->warningColor));
    }

    /**
     * @param  string|array<int|string,string>|Closure  $color
     */
    public function color(string|array|Closure $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string|array<int|string,string>|null
     */
    public function getColor(): string|array|null
    {
        return $this->normalizeColor($this->evaluate($this->color));
    }

    /**
     * Normalize color input (alias support) to the same format ProgressBarColumn uses.
     *
     * @param  string|array<int|string,string>|null  $color
     * @return string|array<int|string,string>|null
     */
    protected function normalizeColor(string|array|null $color): string|array|null
    {
        if ($color === null) {
            return null;
        }

        $lower = strtolower(trim(is_array($color) ? (string) ($color[0] ?? '') : (string) $color));
        $aliases = [
            'danger' => Color::Red[500],
            'warning' => Color::Amber[500],
            'primary' => Color::Blue[500],
        ];

        if (isset($aliases[$lower])) {
            $resolved = $aliases[$lower];
            $resolvedArray = (array) $resolved;
            $value = reset($resolvedArray);

            return Color::convertToRgb((string) $value);
        }

        return Color::convertToRgb($color);
    }

    /**
     * Resolve a progress color for a given status string ('danger','warning','success').
     * Mirrors the logic in ProgressBarColumn::getProgressColor so views render the same colors.
     *
     * @return string|array<int|string,string>
     */
    public function getProgressColorForStatus(string $status): string|array
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
