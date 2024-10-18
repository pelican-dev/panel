<?php

namespace App\Filament\App\Widgets;

use App\Models\Server;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class ServerCpuChart extends ChartWidget
{
    protected static ?string $pollingInterval = '5s';
    protected static ?string $maxHeight = '300px';

    public ?Model $record = null;

    protected function getData(): array
    {
        /** @var Server $server */
        $server = $this->record;

        $cpu = collect(cache()->get("servers.$server->id.cpu_absolute"))
            ->slice(-10)
            ->map(fn ($value, $key) => [
                'cpu' => Number::format($value, maxPrecision: 2, locale: auth()->user()->language),
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
        /** @var Server $server */
        $server = $this->record;

        $cpu = Number::format(collect(cache()->get("servers.$server->id.cpu_absolute"))->last(), maxPrecision: 2, locale: auth()->user()->language);
        $max = Number::format($server->cpu, locale: auth()->user()->language) . '%';

        return 'CPU - ' . $cpu . '% Of ' . $max;
    }
}
