<?php

namespace App\Filament\Server\Widgets;

use App\Models\Server;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class ServerNetworkChart extends ChartWidget
{
    protected static ?string $pollingInterval = '1s';

    protected static ?string $maxHeight = '200px';

    public ?Server $server = null;

    protected function getData(): array
    {
        $previous = null;

        $net = collect(cache()->get("servers.{$this->server->id}.network"))
            ->slice(-10)
            ->map(function ($current, $timestamp) use (&$previous) {
                $net = null;
                if ($previous !== null) {
                    $net = [
                        'rx' => max(0, $current->rx_bytes - $previous->rx_bytes),
                        'tx' => max(0, $current->tx_bytes - $previous->tx_bytes),
                        'timestamp' => Carbon::createFromTimestamp($timestamp, auth()->user()->timezone ?? 'UTC')->format('H:i:s'),
                    ];
                }
                $previous = $current;

                return $net;
            })
            ->all();

        return [
            'datasets' => [
                [
                    'label' => 'Inbound',
                    'data' => array_column($net, 'rx'),
                    'backgroundColor' => [
                        'rgba(96, 165, 250, 0.3)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                ],
                [
                    'label' => 'Outbound',
                    'data' => array_column($net, 'tx'),
                    'backgroundColor' => [
                        'rgba(165, 96, 250, 0.3)',
                    ],
                    'tension' => '0.3',
                    'fill' => true,
                ],
            ],
            'labels' => array_column($net, 'timestamp'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): RawJs
    {
        // TODO: use "panel.use_binary_prefix" config value
        return RawJs::make(<<<'JS'
        {
            scales: {
                x: {
                    display: false,
                },
                y: {
                    min: 0,
                    ticks: {
                        display: true,
                        callback(value) {
                            const bytes = typeof value === 'string' ? parseInt(value, 10) : value;

                            if (bytes < 1) return '0 Bytes';

                            const i = Math.floor(Math.log(bytes) / Math.log(1024));
                            const number = Number((bytes / Math.pow(1024, i)).toFixed(2));

                            return `${number} ${['Bytes', 'KiB', 'MiB', 'GiB', 'TiB'][i]}`;
                        },
                    },
                },
            }
        }
    JS);
    }

    public function getHeading(): string
    {
        return 'Network';
    }
}
