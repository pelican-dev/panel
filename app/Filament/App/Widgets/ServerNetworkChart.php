<?php

namespace App\Filament\App\Widgets;

use App\Models\Server;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class ServerNetworkChart extends ChartWidget
{
    protected static ?string $heading = 'Network';
    protected static ?string $pollingInterval = '1s';
    protected static ?string $maxHeight = '300px';

    public ?Server $server = null;

    protected function getData(): array
    {
        // TODO
        return [];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        display: false,
                    },
                },
                y: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        display: false,
                    },
                },
            }
        }
    JS);
    }
}
