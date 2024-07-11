<?php

namespace App\Filament\Resources\NodeResource\Widgets;

use App\Models\Node;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class NodeCpuChart extends ChartWidget
{
    protected static ?string $pollingInterval = '5s';
    protected static ?string $maxHeight = '300px';

    public ?Model $record = null;

    protected function getData(): array
    {
        /** @var Node $node */
        $node = $this->record;

        $cpu = collect(cache()->get("nodes.$node->id.cpu_percent"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'cpu' => $value,
                'timestamp' => Carbon::createFromTimestamp($key, (auth()->user()->timezone ?? 'UTC'))->format('H:i:s'),
            ])
            ->all();

        return [
            'datasets' => [
                [
                    'data' => array_column($cpu, 'cpu'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.3)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                    'label' => 'Current CPU',
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
                    max: 100,
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
        /** @var Node $node */
        $node = $this->record;

        $cpu = collect(cache()->get("nodes.$node->id.cpu_percent"))->last();

        return 'CPU - ' . number_format($cpu, 2) . ' %';
    }
}
