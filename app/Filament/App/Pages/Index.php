<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Index extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.index';

    public array $history = [];
    public int $historyIndex = 0;

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

        //        setHistory((prevHistory) => [command, ...prevHistory!].slice(0, 32));
        //            setHistoryIndex(-1);
        //
        //            instance && instance.send('send command', command);
        //            e.currentTarget.value = '';
    }

}
