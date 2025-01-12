<?php

namespace App\Filament\Admin\Resources\NodeResource\Widgets;

use App\Models\Node;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class NodeStorageChart extends ChartWidget
{
    protected static ?string $heading = 'Storage';

    protected static ?string $pollingInterval = '360s';

    protected static ?string $maxHeight = '200px';

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
        /** @var Node $node */
        $node = $this->record;

        $total = Number::format(($node->statistics()['disk_total'] ?? 0) / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language);
        $used = Number::format(($node->statistics()['disk_used'] ?? 0) / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language);

        $unused = $total - $used;

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
            'labels' => ['Used', 'Unused'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    public function getHeading(): string
    {
        /** @var Node $node */
        $node = $this->record;

        return 'Storage - ' . convert_bytes_to_readable($node->statistics()['disk_used'] ?? 0) . ' Of ' . convert_bytes_to_readable($node->statistics()['disk_total'] ?? 0);
    }
}
