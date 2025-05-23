<?php

namespace App\Filament\Server\Components;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\View\View;

class SmallStatBlock extends Stat
{
    use EvaluatesClosures;

    protected bool|Closure $copyOnClick = false;

    public function copyOnClick(bool|Closure $copyOnClick = true): static
    {
        $this->copyOnClick = $copyOnClick;

        return $this;
    }

    public function shouldCopyOnClick(): bool
    {
        return $this->evaluate($this->copyOnClick);
    }

    public function render(): View
    {
        return view('filament.components.server-small-data-block', $this->data());
    }
}
