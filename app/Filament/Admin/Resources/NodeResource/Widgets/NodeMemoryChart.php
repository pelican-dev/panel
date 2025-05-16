<?php

namespace App\Filament\Admin\Resources\NodeResource\Widgets;

use App\Models\Node;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class NodeMemoryChart extends ChartWidget
{
    protected static ?string $pollingInterval = '1s';

    protected static ?string $maxHeight = '300px';

    public Node $node;

    /**
     * @var array<int, array{memory: string, timestamp: string}>
     */
    protected array $memoryHistory = [];

    protected function getData(): array
    {
        $data = $this->node->statistics();
        $value = $data['memory_used'];

        $this->memoryHistory = session()->get('memoryHistory', []);
        $this->memoryHistory[] = [
            'memory' => round(config('panel.use_binary_prefix')
                ? $value / 1024 / 1024 / 1024
                : $value / 1000 / 1000 / 1000, 2),
            'timestamp' => now(auth()->user()->timezone ?? 'UTC')->format('H:i:s'),
        ];

        $this->memoryHistory = array_slice($this->memoryHistory, -60);
        session()->put('memoryHistory', $this->memoryHistory);

        return [
            'datasets' => [
                [
                    'data' => array_column($this->memoryHistory, 'memory'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.3)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                ],
            ],
            'labels' => array_column($this->memoryHistory, 'timestamp'),
            'locale' => auth()->user()->language ?? 'en',
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
        $latestMemoryUsed = array_slice(end($this->memoryHistory), -60);
        $totalMemory = $this->node->statistics()['memory_total'];

        $used = config('panel.use_binary_prefix')
            ? Number::format($latestMemoryUsed['memory'], maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($latestMemoryUsed['memory'], maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        $total = config('panel.use_binary_prefix')
            ? Number::format($totalMemory / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($totalMemory / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        return trans('admin/node.memory_chart', ['used' => $used, 'total' => $total]);
    }
}
