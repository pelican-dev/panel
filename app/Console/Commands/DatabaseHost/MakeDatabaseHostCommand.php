<?php

namespace App\Console\Commands\DatabaseHost;

use Illuminate\Console\Command;
use App\Services\Databases\Hosts\HostCreationService;

class MakeDatabaseHostCommand extends Command
{
    protected $signature = 'p:databasehost:make
                            {--name= : A name to identify the database host.}
                            {--host= : The IP address that the database host should connect to create new databases}
                            {--port= : Enter the database host port.}
                            {--username= : Enter the database host username.}
                            {--password= : Enter the database host password.}
                            {--maxDatabases= : Enter the database host maximum number of databases.}';

    protected $description = 'Creates a new database host on the system via the CLI.';

    /**
     * MakeDatabaseHostCommand constructor.
     */
    public function __construct(private HostCreationService $creationService)
    {
        parent::__construct();
    }

    /**
     * Handle the command execution process.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function handle(): void
    {
        $data['name'] = $this->option('name') ?? $this->ask(__('commands.make_databasehost.name'));
        $data['host'] = $this->option('host') ?? $this->ask(__('commands.make_databasehost.host'));
        $data['port'] = $this->option('port') ?? $this->ask(__('commands.make_databasehost.port'), '3306');
        $data['username'] = $this->option('username') ?? $this->ask(__('commands.make_databasehost.username'));
        $data['password'] = $this->option('password') ?? $this->secret(__('commands.make_databasehost.password'));
        $data['max_databases'] = $this->option('maxDatabases') ?? $this->ask(__('commands.make_databasehost.max_databases'));

        $databaseHost = $this->creationService->handle($data);
        $this->line(__('commands.make_databasehost.succes1') . $data['name'] . ' ' . __('commands.make_databasehost.succes2')  . $databaseHost->id . '.');
    }
}
