<?php

namespace App\Filament\Server\Widgets;

use App\Models\Server;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class ServerNetworkChart extends ChartWidget
{
    protected static ?string $heading = 'Network';

    protected static ?string $pollingInterval = '1s';

    protected static ?string $maxHeight = '300px';

    public ?Server $server = null;

    protected function getData(): array
    {
        $data = cache()->get("servers.{$this->server->id}.network");

        $rx = collect($data)
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'rx' => $value->rx_bytes,
                'timestamp' => Carbon::createFromTimestamp($key, (auth()->user()->timezone ?? 'UTC'))->format('H:i:s'),
            ])
            ->all();

        $tx = collect($data)
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'tx' => $value->rx_bytes,
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
                        display: true,
                    },
                    display: false, //debug
                },
                y: {
                    ticks: {
                        display: true,
                    },
                },
            }
        }
    JS);
    }
}
