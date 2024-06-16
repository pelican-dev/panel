<?php

namespace App\Console\Commands\Database;

use App\Models\DatabaseHost;
use Illuminate\Console\Command;

class DatabaseHostListCommand extends Command
{
    protected $signature = 'p:databasehost:list {--format=text : The output format: "text" or "json". }';

    public function handle(): int
    {
        $databaseHosts = DatabaseHost::query()->get()->map(function (DatabaseHost $databaseHost) {
            return [
                'id' => $databaseHost->id,
                'name' => $databaseHost->name,
                'host' => $databaseHost->host,
                'port' => $databaseHost->port,
                'username' => $databaseHost->username,
                'maxDatabases' => $databaseHost->max_databases,
                'node_id' => $databaseHost->node_id,
            ];
        });

        if ($this->option('format') === 'json') {
            $this->output->write($databaseHosts->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $this->table(['ID', 'Name', 'Host', 'Port', 'Username', 'Max Databases', 'Node id'], $databaseHosts->toArray());
        }

        $this->output->newLine();

        return 0;
    }
}
