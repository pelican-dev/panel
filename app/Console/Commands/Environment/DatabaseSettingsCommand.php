<?php

namespace App\Console\Commands\Environment;

use App\Enums\DatabaseDriver;
use App\Traits\EnvironmentWriterTrait;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

class DatabaseSettingsCommand extends Command
{
    use EnvironmentWriterTrait;

    protected $description = 'Configure database settings for the Panel.';

    protected $signature = 'p:environment:database
                            {--driver= : The database driver backend to use.}
                            {--database= : The database to use.}
                            {--host= : The connection address for the database server.}
                            {--port= : The connection port for the database server.}
                            {--username= : Username to use when connecting to the database server.}
                            {--password= : Password to use for the database server.}';

    protected array $variables = [];

    /**
     * DatabaseSettingsCommand constructor.
     */
    public function __construct(private DatabaseManager $database, private Kernel $console)
    {
        parent::__construct();
    }

    /**
     * Handle command execution.
     */
    public function handle(): int
    {
        $this->error('Changing the database driver will NOT move any database data!');
        $this->error('Please make sure you made a database backup first!');
        $this->error('After changing the driver you will have to manually move the old data to the new database.');
        if (!$this->confirm('Do you want to continue?')) {
            return 1;
        }

        $driverList = DatabaseDriver::getFriendlyNameArray(DatabaseDriver::Sqlite);
        $selected = config('database.default', 'sqlite');
        $this->variables['DB_CONNECTION'] = $this->option('driver') ?? $this->choice(
            'Database Driver',
            $driverList,
            array_key_exists($selected, $driverList) ? $selected : null
        );
        $driver = DatabaseDriver::from($this->variables['DB_CONNECTION']);

        switch ($driver) {
            case DatabaseDriver::Sqlite:
                $this->variables['DB_DATABASE'] = $this->option('database') ?? $this->ask(
                    'Database Path',
                    env('DB_DATABASE', 'database.sqlite')
                );
                break;
            case DatabaseDriver::Mariadb:
            case DatabaseDriver::Mysql:
            case DatabaseDriver::Postgresql:
                $this->output->note(__('commands.database_settings.DB_HOST_note'));
                $this->variables['DB_HOST'] = $this->option('host') ?? $this->ask(
                    'Database Host',
                    $driver->getDefaultOption('host', true)
                );

                $this->variables['DB_PORT'] = $this->option('port') ?? $this->ask(
                    'Database Port',
                    $driver->getDefaultOption('port', true)
                );

                $this->variables['DB_DATABASE'] = $this->option('database') ?? $this->ask(
                    'Database Name',
                    $driver->getDefaultOption('database', true)
                );

                $this->output->note(__('commands.database_settings.DB_USERNAME_note'));
                $this->variables['DB_USERNAME'] = $this->option('username') ?? $this->ask(
                    'Database Username',
                    $driver->getDefaultOption('username', true)
                );

                $askForPassword = true;
                if (!empty($driver->getDefaultOption('password', true)) && $this->input->isInteractive()) {
                    $this->variables['DB_PASSWORD'] = $driver->getDefaultOption('password', true);
                    $askForPassword = $this->confirm(__('commands.database_settings.DB_PASSWORD_note'));
                }

                if ($askForPassword) {
                    $this->variables['DB_PASSWORD'] = $this->option('password') ?? $this->secret('Database Password');
                }

                try {
                    // Test connection
                    DB::build([
                        'driver' => $driver->value,
                        'host' => $this->variables['DB_HOST'],
                        'port' => $this->variables['DB_PORT'],
                        'database' => $this->variables['DB_DATABASE'],
                        'username' => $this->variables['DB_USERNAME'],
                        'password' => $this->variables['DB_PASSWORD'],
                        'charset' => $driver->getDefaultOption('charset', true),
                        'collation' => $driver->getDefaultOption('collation', true),
                        'strict' => true,
                    ])->beginTransaction();
                } catch (\PDOException $exception) {
                    $this->output->error(sprintf('Unable to connect to the %s server using the provided credentials. The error returned was "%s".', $driver->getFriendlyName(), $exception->getMessage()));
                    $this->output->error(__('commands.database_settings.DB_error_2'));

                    if ($this->confirm(__('commands.database_settings.go_back'))) {
                        return $this->handle();
                    }

                    return 1;
                }
                break;
        }

        $this->writeToEnvironment($this->variables);

        $this->info($this->console->output());

        return 0;
    }
}
