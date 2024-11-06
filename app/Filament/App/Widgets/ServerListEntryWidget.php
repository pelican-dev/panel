<?php

namespace App\Filament\App\Widgets;

use App\Filament\Server\Pages\Console;
use App\Models\Server;
use Carbon\CarbonInterface;
use Filament\Widgets\Widget;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class ServerListEntryWidget extends Widget
{
    protected static string $view = 'filament.components.server-list-entry';

    public ?Server $server = null;

    public function getViewData(): array
    {
        return [
            'name' => $this->server->name,
            'uptime' => $this->uptime(),
            'cpu' => $this->cpu(),
            'memory' => $this->memory(),
            'disk' => $this->disk(),
            'status' => Str::title($this->server->condition),
            'icon' => $this->server->conditionIcon(),
            'color' => $this->server->conditionColor(),
        ];
    }

    public function openServer(): void
    {
        $this->redirect(Console::getUrl(panel: 'server', tenant: $this->server));
    }

    private function uptime(): string
    {
        $uptime = collect(cache()->get("servers.{$this->server->id}.uptime"))->last() ?? 0;

        if ($uptime === 0) {
            return 'Offline';
        }

        return now()->subMillis($uptime)->diffForHumans(syntax: CarbonInterface::DIFF_ABSOLUTE, short: true, parts: 2);
    }

    private function cpu(): string
    {
        $cpu = Number::format(collect(cache()->get("servers.{$this->server->id}.cpu_absolute"))->last() ?? 0, maxPrecision: 2, locale: auth()->user()->language) . '%';
        $max = Number::format($this->server->cpu, locale: auth()->user()->language) . '%';

        return $cpu . ($this->server->cpu > 0 ? ' Of ' . $max : '');
    }

    private function memory(): string
    {
        $latestMemoryUsed = collect(cache()->get("servers.{$this->server->id}.memory_bytes"))->last() ?? 0;
        $totalMemory = collect(cache()->get("servers.{$this->server->id}.memory_limit_bytes"))->last() ?? 0;

        $used = config('panel.use_binary_prefix')
            ? Number::format($latestMemoryUsed / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($latestMemoryUsed / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        if ($totalMemory === 0) {
            $total = config('panel.use_binary_prefix')
                ? Number::format($this->server->memory / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
                : Number::format($this->server->memory / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';
        } else {
            $total = config('panel.use_binary_prefix')
                ? Number::format($totalMemory / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
                : Number::format($totalMemory / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';
        }

        return $used . ($this->server->memory > 0 ? ' Of ' . $total : '');
    }

    private function disk(): string
    {
        $usedDisk = collect(cache()->get("servers.{$this->server->id}.disk_bytes"))->last() ?? 0;

        $used = config('panel.use_binary_prefix')
            ? Number::format($usedDisk / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($usedDisk / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        $total = config('panel.use_binary_prefix')
            ? Number::format($this->server->disk / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($this->server->disk / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        return $used . ($this->server->disk > 0 ? ' Of ' . $total : '');
    }
}
