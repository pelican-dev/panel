<?php

namespace App\Filament\Server\Widgets;

use App\Models\Server;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class ServerCpuChart extends ChartWidget
{
    protected static ?string $pollingInterval = '1s';

    protected static ?string $maxHeight = '200px';

    public ?Server $server = null;

    protected function getData(): array
    {
        $cpu = collect(cache()->get("servers.{$this->server->id}.cpu_absolute"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'cpu' => Number::format($value, maxPrecision: 2),
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
        $cpu = Number::format(collect(cache()->get("servers.{$this->server->id}.cpu_absolute"))->last() ?? 0, maxPrecision: 2, locale: auth()->user()->language) . '%';
        $max = Number::format($this->server->cpu, locale: auth()->user()->language) . '%';

        return 'CPU - ' . $cpu . ($this->server->cpu > 0 ? ' Of ' . $max : '');
    }
}
