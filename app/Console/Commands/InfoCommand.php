<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InfoCommand extends Command
{
    protected $description = 'Displays the application, database, email and backup configurations along with the panel version.';

    protected $signature = 'p:info';

    public function handle(): void
    {
        $this->call('about');
    }
}
