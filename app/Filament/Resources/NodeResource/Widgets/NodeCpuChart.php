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

    public ?Model $record = null;

    protected static ?array $options = [
        'scales' => [
            'y' => [
                'suggestedMax' => '0.5',
            ],
        ],
    ];

    protected function getData(): array
    {
        /** @var Node $node */
        $node = $this->record;

        //        $cpu = ($node->statistics()['cpu_percent'] ?? 0);
        //        $timestamp = now()->format('H:i:s');

        $data = collect(cache()->get("nodes.$node->id.cpu"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'cpu' => $value,
                'timestamp' => Carbon::createFromTimestamp($key)->format('H:i:s'),
            ])
            ->all();

        // Prepare the datasets and labels
        return [
            'datasets' => [
                [
                    'data' => array_column($data, 'cpu'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.2)',
                    ],
                    'tension' => '1',
                    'fill' => true,
                ],
            ],
            'labels' => array_column($data, 'timestamp'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
