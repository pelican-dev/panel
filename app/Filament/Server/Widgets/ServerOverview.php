<?php

namespace App\Filament\Server\Widgets;

use App\Enums\ContainerStatus;
use App\Filament\Server\Components\SmallStatBlock;
use App\Models\Server;
use Carbon\CarbonInterface;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Number;

class ServerOverview extends StatsOverviewWidget
{
    protected static ?string $pollingInterval = '1s';

    public ?Server $server = null;

    protected function getStats(): array
    {
        return [
            SmallStatBlock::make('Name', $this->server->name)
                ->extraAttributes([
                    'class' => 'overflow-x-auto',
                ]),
            SmallStatBlock::make('Status', $this->status()),
            SmallStatBlock::make('Address', $this->server->allocation->address)
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
        $status = $this->server->condition->getLabel();
        $uptime = collect(cache()->get("servers.{$this->server->id}.uptime"))->last() ?? 0;

        if ($uptime === 0) {
            return $status;
        }

        $uptime = now()->subMillis($uptime)->diffForHumans(syntax: CarbonInterface::DIFF_ABSOLUTE, short: true, parts: 2);

        return "$status ($uptime)";
    }

    public function cpuUsage(): string
    {
        $status = $this->server->retrieveStatus();

        if ($status->isOffline()) {
            return ContainerStatus::Offline->getLabel();
        }

        $data = collect(cache()->get("servers.{$this->server->id}.cpu_absolute"))->last(default: 0);
        $cpu = Number::format($data, maxPrecision: 2, locale: auth()->user()->language) . ' %';

        return $cpu . ($this->server->cpu > 0 ? ' / ' . Number::format($this->server->cpu, locale: auth()->user()->language) . ' %' : ' / ∞');
    }

    public function memoryUsage(): string
    {
        $status = $this->server->retrieveStatus();

        if ($status->isOffline()) {
            return ContainerStatus::Offline->getLabel();
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

        $totalBytes = $this->server->disk * (config('panel.use_binary_prefix') ? 1024 * 1024 : 1000 * 1000);

        $used = convert_bytes_to_readable($disk);
        $total = convert_bytes_to_readable($totalBytes);

        return $used . ($this->server->disk > 0 ? ' / ' . $total : ' / ∞');
    }
}
