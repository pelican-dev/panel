<?php

namespace App\Filament\Server\Widgets;

use App\Filament\Server\Components\StatBlock;
use App\Models\Server;
use Carbon\CarbonInterface;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Str;

class ServerOverview extends StatsOverviewWidget
{
    protected static ?string $pollingInterval = '1s';

    public ?Server $server = null;

    protected function getStats(): array
    {
        return [
            StatBlock::make('Name', $this->server->name)
                ->description($this->server->description)
                ->extraAttributes([
                    'class' => 'overflow-x-auto',
                ]),
            StatBlock::make('Status', $this->status()),
            StatBlock::make('Address', $this->server->allocation->address)
                ->extraAttributes([
                    'class' => 'overflow-x-auto',
                ]),
        ];
    }

    private function status(): string
    {
        $status = Str::title($this->server->condition);
        $uptime = collect(cache()->get("servers.{$this->server->id}.uptime"))->last() ?? 0;

        if ($uptime === 0) {
            return $status;
        }

        $uptime = now()->subMillis($uptime)->diffForHumans(syntax: CarbonInterface::DIFF_ABSOLUTE, short: true, parts: 2);

        return "$status ($uptime)";
    }
}
