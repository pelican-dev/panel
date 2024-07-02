<?php

namespace App\Filament\Resources\NodeResource\Widgets;

use App\Models\Node;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class NodeCpuChart extends ChartWidget
{
    protected static ?string $heading = 'CPU';
    protected static ?string $pollingInterval = '5s';
    protected static ?string $maxHeight = '300px';
    public ?Model $record = null;

    protected static ?array $options = [
        'scales' => [
            'y' => [
                'max' => 100,
                'min' => 0,
            ],
        ],
    ];

    protected function getData(): array
    {
        /** @var Node $node */
        $node = $this->record;

        //        $cpu = ($node->statistics()['cpu_percent'] ?? 0);
        //        $timestamp = now()->format('H:i:s');

        $cpu = collect(cache()->get("nodes.$node->id.cpu_percent"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'cpu' => $value,
                'timestamp' => Carbon::createFromTimestamp($key)->format('H:i:s'),
            ])
            ->all();
        $cpu1 = collect(cache()->get("nodes.$node->id.load_average1"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'cpu1' => $value,
                'timestamp' => Carbon::createFromTimestamp($key)->format('H:i:s'),
            ])
            ->all();
        $cpu5 = collect(cache()->get("nodes.$node->id.load_average5"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'cpu5' => $value,
                'timestamp' => Carbon::createFromTimestamp($key)->format('H:i:s'),
            ])
            ->all();
        $cpu15 = collect(cache()->get("nodes.$node->id.load_average15"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'cpu15' => $value,
                'timestamp' => Carbon::createFromTimestamp($key)->format('H:i:s'),
            ])
            ->all();

        // Prepare the datasets and labels
        return [
            'datasets' => [
                [
                    'data' => array_column($cpu, 'cpu'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.2)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                    'label' => 'Current CPU',
                ],
                [
                    'data' => array_column($cpu1, 'cpu1'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.2)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                    'label' => 'Load Average - 1m',
                ],
                [
                    'data' => array_column($cpu5, 'cpu5'),
                    'backgroundColor' => [
                        'rgba(255, 165, 250, 0.2)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                    'label' => 'Load Average - 5m',
                ],
                [
                    'data' => array_column($cpu15, 'cpu15'),
                    'backgroundColor' => [
                        'rgba(255, 165, 250, 0.2)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                    'label' => 'Load Average - 15m',
                ],
            ],
            'labels' => array_column($cpu, 'timestamp'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
