<?php

namespace App\Filament\Server\Components;

use BackedEnum;
use Closure;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Concerns\CanOpenUrl;
use Filament\Schemas\Components\Concerns\HasDescription;
use Illuminate\Contracts\Support\Htmlable;

class SmallStatBlock extends Component
{
    use CanOpenUrl;
    use HasDescription;

    protected string $view = 'filament.components.server-small-data-block';

    protected string|BackedEnum|null $icon = null;

    protected string $value;

    final public function __construct(string $label, string $value)
    {
        $this->label($label);
        $this->value($value);
    }

    /**
     * @return SmallStatBlock
     */
    public static function make(string $label, string $value): static
    {
        return app(static::class, ['label' => $label, 'value' => $value]);
    }

    public function icon(string|BackedEnum|null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return SmallStatBlock
     */
    private function value(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return scalar | Htmlable | Closure
     */
    public function getValue(): mixed
    {
        return value($this->value);
    }
}
