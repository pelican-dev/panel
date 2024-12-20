<?php

namespace App\Filament\Admin\Resources\NodeResource\Widgets;

use App\Models\Node;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class NodeCpuChart extends ChartWidget
{
    protected static ?string $pollingInterval = '5s';

    protected static ?string $maxHeight = '300px';

    public ?Model $record = null;

    protected function getData(): array
    {
        /** @var Node $node */
        $node = $this->record;
        $threads = $node->systemInformation()['cpu_count'] ?? 0;

        $cpu = collect(cache()->get("nodes.$node->id.cpu_percent"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'cpu' => Number::format($value * $threads, maxPrecision: 2),
                'timestamp' => Carbon::createFromTimestamp($key, auth()->user()->timezone ?? 'UTC')->format('H:i:s'),
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
                ],
            ],
            'labels' => array_column($cpu, 'timestamp'),
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
        /** @var Node $node */
        $node = $this->record;
        $threads = $node->systemInformation()['cpu_count'] ?? 0;

        $cpu = Number::format(collect(cache()->get("nodes.$node->id.cpu_percent"))->last() * $threads, maxPrecision: 2, locale: auth()->user()->language);
        $max = Number::format($threads * 100, locale: auth()->user()->language) . '%';

        return 'CPU - ' . $cpu . '% Of ' . $max;
    }
}
