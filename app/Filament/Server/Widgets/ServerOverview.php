<?php

namespace App\Filament\Server\Widgets;

use App\Models\Server;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;
use App\Enums\ContainerStatus;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget;
use App\Filament\Server\Components\StatBlock;
use App\Filament\Server\Components\SmallStatBlock;

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
            SmallStatBlock::make('CPU', $this->cpuUsage()),
            SmallStatBlock::make('Memory', $this->memoryUsage()),
            SmallStatBlock::make('Disk', $this->diskUsage()),
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

    public function cpuUsage(): string
    {
        $status = ContainerStatus::tryFrom($this->server->retrieveStatus());

        if ($status === ContainerStatus::Offline || $status === ContainerStatus::Missing) {
            return 'Offline';
        }

        $data = collect(cache()->get("servers.{$this->server->id}.cpu_absolute"))->last(default: 0);
        $cpu = Number::format($data, maxPrecision: 2, locale: auth()->user()->language) . ' %';

        return $cpu . ($this->server->cpu > 0 ? ' / ' . Number::format($this->server->cpu, locale: auth()->user()->language) . ' %' : ' / ∞');
    }

    public function memoryUsage(): string
    {
        $status = ContainerStatus::tryFrom($this->server->retrieveStatus());

        if ($status === ContainerStatus::Offline || $status === ContainerStatus::Missing) {
            return 'Offline';
        }

        $latestMemoryUsed = collect(cache()->get("servers.{$this->server->id}.memory_bytes"))->last(default: 0);
        $totalMemory = collect(cache()->get("servers.{$this->server->id}.memory_limit_bytes"))->last(default: 0);

        $used = convert_bytes_to_readable($latestMemoryUsed);
        $total = convert_bytes_to_readable($totalMemory);

        return $used . ($this->server->memory > 0 ? ' / ' . $total : ' / ∞');
    }

    public function diskUsage(): string
    {
        $disk = collect(cache()->get("servers.{$this->server->id}.disk_bytes"))->last(default: 0);

        if ($disk === 0) {
            return 'Unavailable';
        }

        $used = convert_bytes_to_readable($disk);
        $total = convert_bytes_to_readable($this->server->disk);

        return $used . ($this->server->disk > 0 ? ' / ' . $total : ' / ∞');
    }
}
