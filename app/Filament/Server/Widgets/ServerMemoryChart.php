<?php

namespace App\Filament\Server\Widgets;

use App\Models\Server;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class ServerMemoryChart extends ChartWidget
{
    protected static ?string $pollingInterval = '1s';

    protected static ?string $maxHeight = '200px';

    public ?Server $server = null;

    protected function getData(): array
    {
        $memUsed = collect(cache()->get("servers.{$this->server->id}.memory_bytes"))->slice(-10)
            ->map(fn ($value, $key) => [
                'memory' => Number::format(config('panel.use_binary_prefix') ? $value / 1024 / 1024 / 1024 : $value / 1000 / 1000 / 1000, maxPrecision: 2),
                'timestamp' => Carbon::createFromTimestamp($key, auth()->user()->timezone ?? 'UTC')->format('H:i:s'),
            ])
            ->all();

        return [
            'datasets' => [
                [
                    'data' => array_column($memUsed, 'memory'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.3)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                ],
            ],
            'labels' => array_column($memUsed, 'timestamp'),
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
                x: {
                    display: false,
                }
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
        $latestMemoryUsed = collect(cache()->get("servers.{$this->server->id}.memory_bytes"))->last() ?? 0;
        $totalMemory = collect(cache()->get("servers.{$this->server->id}.memory_limit_bytes"))->last() ?? 0;

        $used = config('panel.use_binary_prefix')
            ? Number::format($latestMemoryUsed / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($latestMemoryUsed / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        if ($totalMemory === 0) {
            $total = config('panel.use_binary_prefix')
                ? Number::format($this->server->memory / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
                : Number::format($this->server->memory / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';
        } else {
            $total = config('panel.use_binary_prefix')
                ? Number::format($totalMemory / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
                : Number::format($totalMemory / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';
        }

        return 'Memory - ' . $used . ($this->server->memory > 0 ? ' Of ' . $total : '');
    }
}
