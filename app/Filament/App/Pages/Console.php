<?php

namespace App\Filament\App\Pages;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class Console extends Page
{
    protected static ?string $navigationIcon = 'tabler-brand-tabler';

    protected static string $view = 'filament.app.pages.console';

    public array $history = [];
    public int $historyIndex = 0;
    public string $input = '';

    protected function getViewData(): array
    {
        return [
            'server' => Filament::getTenant(),
            'user' => auth()->user(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('start')
                ->color('primary')
                ->action(fn () => $this->dispatch('setServerState', state: 'start')),

            Action::make('restart')
                ->color('gray')
                ->action(fn () => $this->dispatch('setServerState', state: 'restart')),

            Action::make('stop')
                ->color('danger')
                ->action(fn () => $this->dispatch('setServerState', state: 'stop')),
        ];
    }

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

}
