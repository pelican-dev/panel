<?php

namespace App\Filament\App\Widgets;

use App\Models\Server;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class ServerNetworkChart extends ChartWidget
{
    protected static ?string $heading = 'Network';
    protected static ?string $pollingInterval = '1s';
    protected static ?string $maxHeight = '300px';

    public ?Server $server = null;

    protected function getData(): array
    {
        $rx = collect(cache()->get("servers.{$this->server->id}.rx_bytes"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'rx' => Number::format($value, maxPrecision: 2, locale: auth()->user()->language),
                'timestamp' => Carbon::createFromTimestamp($key, (auth()->user()->timezone ?? 'UTC'))->format('H:i:s'),
            ])
            ->all();

        $tx = collect(cache()->get("servers.{$this->server->id}.tx_bytes"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'tx' => Number::format($value, maxPrecision: 2, locale: auth()->user()->language),
                'timestamp' => Carbon::createFromTimestamp($key, (auth()->user()->timezone ?? 'UTC'))->format('H:i:s'),
            ])
            ->all();

        return [
            'datasets' => [
                [
                    'label' => 'Inbound',
                    'data' => array_column($rx, 'rx'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.3)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                ],
                [
                    'label' => 'Outbound',
                    'data' => array_column($tx, 'tx'),
                    'backgroundColor' => [
                        'rgba(165, 96, 250, 0.3)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                ],
            ],
            'labels' => array_column($rx, 'timestamp'),
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
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        display: false,
                    },
                },
                y: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        display: false,
                    },
                },
            }
        }
    JS);
    }
}
