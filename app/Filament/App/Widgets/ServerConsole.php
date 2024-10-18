<?php

namespace App\Filament\App\Widgets;

use App\Models\Server;
use App\Models\User;
use Filament\Widgets\Widget;

class ServerConsole extends Widget
{
    protected static string $view = 'filament.components.server-console';

    protected int|string|array $columnSpan = 'full';

    public ?Server $server = null;
    public ?User $user = null;

    public array $history = [];
    public int $historyIndex = 0;

    public string $input = '';

    public function up()
    {
        $this->historyIndex = min($this->historyIndex + 1, count($this->history) - 1);

        //        e.currentTarget.value = history![newIndex] || '';
        //
        //        // By default up arrow will also bring the cursor to the start of the line, so we'll preventDefault to keep it at the end.
        //        e.preventDefault();
    }

    public function down()
    {
        $this->historyIndex = max($this->historyIndex - 1, -1);

        // e.currentTarget.value = history![newIndex] || '';
    }

    public function enter()
    {
        $this->dispatch('sendServerCommand', command: $this->input);

        $this->input = '';

        //        setHistory((prevHistory) => [command, ...prevHistory!].slice(0, 32));
        //            setHistoryIndex(-1);
        //
        //            instance && instance.send('send command', command);
        //            e.currentTarget.value = '';
    }

    public function storeStats(array $data)
    {
        $timestamp = now()->getTimestamp();

        foreach ($data as $key => $value) {
            $cacheKey = "servers.{$this->server->id}.$key";
            $data = cache()->get($cacheKey, []);

            $data[$timestamp] = $value;

            cache()->put($cacheKey, $data, now()->addMinute());
        }
    }
}
