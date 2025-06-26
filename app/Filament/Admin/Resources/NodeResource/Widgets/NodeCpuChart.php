<?php

namespace App\Filament\Admin\Resources\NodeResource\Widgets;

use App\Models\Node;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class NodeCpuChart extends ChartWidget
{
    protected static ?string $pollingInterval = '5s';

    protected static ?string $maxHeight = '300px';

    public Node $node;

    /**
     * @var array<int, array{cpu: string, timestamp: string}>
     */
    protected array $cpuHistory = [];

    protected int $threads = 0;

    protected function getData(): array
    {
        $sessionKey = "node_stats.{$this->node->id}";

        $data = $this->node->statistics();

        $this->threads = session("{$sessionKey}.threads", $this->node->systemInformation()['cpu_count'] ?? 0);

        $this->cpuHistory = session("{$sessionKey}.cpu_history", []);
        $this->cpuHistory[] = [
            'cpu' => round($data['cpu_percent'] * $this->threads, 2),
            'timestamp' => now(auth()->user()->timezone ?? 'UTC')->format('H:i:s'),
        ];

        $this->cpuHistory = array_slice($this->cpuHistory, -60);
        session()->put("{$sessionKey}.cpu_history", $this->cpuHistory);

        return [
            'datasets' => [
                [
                    'data' => array_column($this->cpuHistory, 'cpu'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.3)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                ],
            ],
            'labels' => array_column($this->cpuHistory, 'timestamp'),
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
        $data = array_slice(end($this->cpuHistory), -60);

        $cpu = Number::format($data['cpu'], maxPrecision: 2, locale: auth()->user()->language);
        $max = Number::format($this->threads * 100, locale: auth()->user()->language);

        return trans('admin/node.cpu_chart', ['cpu' => $cpu, 'max' => $max]);
    }
}
