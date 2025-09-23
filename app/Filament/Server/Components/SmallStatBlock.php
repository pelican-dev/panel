<?php

namespace App\Filament\Server\Components;

use Filament\Support\Concerns\CanBeCopied;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SmallStatBlock extends Stat
{
    use CanBeCopied;

    protected string $view = 'filament.components.server-small-data-block';
}
