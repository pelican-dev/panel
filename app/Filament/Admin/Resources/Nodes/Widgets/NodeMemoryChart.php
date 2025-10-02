<?php

namespace App\Filament\Admin\Resources\Nodes\Widgets;

use App\Models\Node;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class NodeMemoryChart extends ChartWidget
{
    protected ?string $pollingInterval = '5s';

    protected ?string $maxHeight = '300px';

    public Node $node;

    /**
     * @var array<int, array{memory: string, timestamp: string}>
     */
    protected array $memoryHistory = [];

    protected int $totalMemory = 0;

    protected function getData(): array
    {
        $sessionKey = "node_stats.{$this->node->id}";

        $data = $this->node->statistics();

        $this->totalMemory = session("{$sessionKey}.total_memory", $data['memory_total']);

        $this->memoryHistory = session("{$sessionKey}.memory_history", []);
        $this->memoryHistory[] = [
            'memory' => round(config('panel.use_binary_prefix')
                ? $data['memory_used'] / 1024 / 1024 / 1024
                : $data['memory_used'] / 1000 / 1000 / 1000, 2),
            'timestamp' => now(user()->timezone ?? 'UTC')->format('H:i:s'),
        ];

        $this->memoryHistory = array_slice($this->memoryHistory, -60);
        session()->put("{$sessionKey}.memory_history", $this->memoryHistory);

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
            'locale' => user()->language ?? 'en',
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

        $used = config('panel.use_binary_prefix')
            ? format_number($latestMemoryUsed['memory'], maxPrecision: 2) .' GiB'
            : format_number($latestMemoryUsed['memory'], maxPrecision: 2) . ' GB';

        $total = config('panel.use_binary_prefix')
            ? format_number($this->totalMemory / 1024 / 1024 / 1024, maxPrecision: 2) .' GiB'
            : format_number($this->totalMemory / 1000 / 1000 / 1000, maxPrecision: 2) . ' GB';

        return trans('admin/node.memory_chart', ['used' => $used, 'total' => $total]);
    }
}
