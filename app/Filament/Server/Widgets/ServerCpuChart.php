<?php

namespace App\Filament\Server\Widgets;

use App\Models\Server;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Number;

class ServerCpuChart extends ChartWidget
{
    protected static ?string $pollingInterval = '1s';

    protected static ?string $maxHeight = '200px';

    public ?Server $server = null;

    public static function canView(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return !$server->isInConflictState() && !$server->retrieveStatus()->isOffline();
    }

    protected function getData(): array
    {
        $period = auth()->user()->getCustomization()['console_graph_period'] ?? 30;
        $cpu = collect(cache()->get("servers.{$this->server->id}.cpu_absolute"))
            ->slice(-$period)
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
        return trans('server/console.labels.cpu');
    }
}
