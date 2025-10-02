<?php

namespace App\Filament\Server\Widgets;

use App\Enums\CustomizationKey;
use App\Models\Server;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class ServerMemoryChart extends ChartWidget
{
    protected ?string $pollingInterval = '1s';

    protected ?string $maxHeight = '200px';

    public ?Server $server = null;

    public static function canView(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return !$server->isInConflictState() && !$server->retrieveStatus()->isOffline();
    }

    protected function getData(): array
    {
        $period = (int) user()?->getCustomization(CustomizationKey::ConsoleGraphPeriod);
        $memUsed = collect(cache()->get("servers.{$this->server->id}.memory_bytes"))
            ->slice(-$period)
            ->map(fn ($value, $key) => [
                'memory' => round(config('panel.use_binary_prefix') ? $value / 1024 / 1024 / 1024 : $value / 1000 / 1000 / 1000, 2),
                'timestamp' => Carbon::createFromTimestamp($key, user()->timezone ?? 'UTC')->format('H:i:s'),
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
        return trans('server/console.labels.memory');
    }
}
