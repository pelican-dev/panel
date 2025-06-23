<?php

namespace App\Filament\Server\Components;

use Closure;
use Filament\Schemas\Components\Component;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\View\View;

class SmallStatBlock extends Component
{
    use EvaluatesClosures;

    protected bool|Closure $copyOnClick = false;

    public function copyOnClick(bool|Closure $copyOnClick = true): static
    {
        $this->copyOnClick = $copyOnClick;
    }

    protected string $value;

    public function shouldCopyOnClick(): bool
    {
        return $this->evaluate($this->copyOnClick);
    }

    public function render(): View
    {
        return value($this->value);
    }
}
