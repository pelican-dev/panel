<?php

namespace App\Filament\Admin\Resources\NodeResource\Widgets;

use App\Models\Node;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class NodeStorageChart extends ChartWidget
{
    protected static ?string $pollingInterval = '360s';

    protected static ?string $maxHeight = '200px';

    public Node $node;

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
        $total = config('panel.use_binary_prefix')
            ? ($this->node->statistics()['disk_total']) / 1024 / 1024 / 1024
            : ($this->node->statistics()['disk_total']) / 1000 / 1000 / 1000;
        $used = config('panel.use_binary_prefix')
            ? ($this->node->statistics()['disk_used']) / 1024 / 1024 / 1024
            : ($this->node->statistics()['disk_used']) / 1000 / 1000 / 1000;

        $unused = $total - $used;

        $used = Number::format($used, maxPrecision: 2);
        $unused = Number::format($unused, maxPrecision: 2);

        return [
            'datasets' => [
                [
                    'data' => [$used, $unused],
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(74, 222, 128)',
                        'rgb(255, 205, 86)',
                    ],
                ],
            ],
            'labels' => [trans('admin/node.used'), trans('admin/node.unused')],
            'locale' => auth()->user()->language ?? 'en',
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    public function getHeading(): string
    {
        $used = convert_bytes_to_readable($this->node->statistics()['disk_used']);
        $total = convert_bytes_to_readable($this->node->statistics()['disk_total']);

        return trans('admin/node.disk_chart', ['used' => $used, 'total' => $total]);
    }
}
