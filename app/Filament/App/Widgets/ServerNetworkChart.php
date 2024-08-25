<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class ServerNetworkChart extends ChartWidget
{
    protected static ?string $heading = 'Network';
    protected static ?string $pollingInterval = '60s';
    protected static ?string $maxHeight = '300px';

    public ?Model $record = null;

    protected static ?array $options = [
        'scales' => [
            'x' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ],
            ],
            'y' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ],
            ],
        ],
    ];

    protected function getData(): array
    {

        return [];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
