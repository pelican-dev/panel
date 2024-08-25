<?php

namespace App\Filament\App\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class ServerCpuChart extends ChartWidget
{
    protected static ?string $pollingInterval = '5s';
    protected static ?string $maxHeight = '300px';

    public ?Model $record = null;

    protected function getData(): array
    {
        $cpu = [];

        return [
            'datasets' => [
                [
                    'data' => array_column($cpu, 'cpu'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.3)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                ],
            ],
            'labels' => array_column($cpu, 'timestamp'),
        ];
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
                y: {
                    min: 0,
                },
            },
            plugins: {
                legend: {
                    display: false,
                }
            }
        }
    JS);
    }

    public function getHeading(): string
    {
        return 'CPU - $current% Of $max%';
    }
}
