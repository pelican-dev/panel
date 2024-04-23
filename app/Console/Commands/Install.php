<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\Commands\EnvironmentWriterTrait;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Console\Kernel;
use App\Traits\Helpers\AvailableLanguages;
use App\Services\Users\UserCreationService;
use Illuminate\Support\Facades\DB;
use Exception;

class Install extends Command
{
    use EnvironmentWriterTrait;
    use AvailableLanguages;

    private $database;
    private $console;
    private $creationService;

    public function __construct(DatabaseManager $database, Kernel $console, UserCreationService $creationService)
    {
        parent::__construct();
        $this->database = $database;
        $this->console = $console;
        $this->creationService = $creationService;
    }    

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p:install
                            {--new-salt : Whether or not to generate a new salt for Hashids.}
                            {--author= : The email that services created on this instance should be linked to.}
                            {--url= : The URL that this Panel is running on.}
                            {--timezone= : The timezone to use for Panel times.}
                            {--cache= : The cache driver backend to use.}
                            {--session= : The session driver backend to use.}
                            {--queue= : The queue driver backend to use.}
                            {--redis-host= : Redis host to use for connections.}
                            {--redis-pass= : Password used to connect to redis.}
                            {--redis-port= : Port to connect to redis over.}
                            {--settings-ui= : Enable or disable the settings UI.}
                            {--host= : The connection address for the MySQL server.}
                            {--port= : The connection port for the MySQL server.}
                            {--database= : The database to use.}
                            {--username= : Username to use when connecting.}
                            {--password= : Password to use for this database.}
                            {--email=} 
                            {--username=} 
                            {--name-first=} 
                            {--name-last=} 
                            {--password=} 
                            {--admin=} 
                            {--no-password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to run when installing your panel for the first time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->output->comment(__("commands.install.first_commit"));
        $first_question = $this->choice(__("commands.install.first_question"), [
            __("commands.install.yes_first"),
            __("commands.install.no_first"),
        ]);

        if ($first_question === __("commands.install.yes_first")) {
            $second_question = $this->choice(__("commands.install.second_question"), [
                __("commands.install.yes_second"),
                __("commands.install.no_second"),
            ]);
            if ($second_question === __("commands.install.yes_second")) {
                //$this->Command("cp .env.example .env");
                //$this->Command("composer install --no-dev --optimize-autoloader");
                //$this->Command("php artisan key:generate --force");
                $this->output->comment("Now we are going to setup your settings.");
                //$this->setup_settings();
                $this->output->comment("Now we are going to setup your database.");
                $database_question = $this->choice(__("commands.install.database_question"), [
                    __("commands.install.yes_database"),
                    __("commands.install.no_database"),
                ]);
                if ($database_question === __("commands.install.no_database")) {
                    $server = $this->ask("What is the IP of the server where your mysql-server is located?", "127.0.0.1");
                    $username = $this->ask("What is the username that you want to use to login to create the pelican database and user?", "root");
                    $password = $this->ask("What is the password of this user?");
                    $password_2 = $this->ask("What do you want the password for pelican user to be?");
                    $ip = $this->ask("What is the serverip of this server?");
                    $temp_file = fopen("database.txt", "w");
                    $text = "DB_HOST=$server\nDB_USERNAME='$username'\nDB_PASSWORD='$password'\nDB_USERPASSWORD='$password_2'\nDB_USERIP='$ip'";
                    fwrite($temp_file, $text);
                    fclose($temp_file);
                    $this->Command("bash /var/www/pelican/app/Console/Commands/Scripts/create_database.sh");
                    sleep(7);
                    $this->Command("rm /var/www/pelican/database.txt");
                    $this->Command("php artisan migrate --seed --force");
                    $this->output->comment("Now we are going to make a user for your panel");
                    $this->setup_first_user();
                    $this->output->comment("Now some final changes and you are good to go");
                    $this->Command("bash /var/www/pelican/app/Console/Commands/Scripts/add_cronjob.sh");
                    $webserver = $this->choice(__("commands.install.webserver_question"), [
                        "NGINX/Apache",
                        "Rocky Linux NGINX",
                        "Rocky Linux Apache"
                    ]);
                    if ($webserver === "NGINX/Apache") {
                        $this->Command("chown -R www-data:www-data /var/www/pelican/* ");
                    } elseif ($webserver === "Rocky Linux NGINX") {
                        $this->Command("chown -R nginx:nginx /var/www/pelican/* ");
                    } elseif ($webserver === "Rocky Linux Apache") {
                        $this->Command("chown -R apache:apache /var/www/pelican/* ");
                    }
                } elseif ($database_question === __("commands.install.yes_database")) {
                    $this->setup_database();
                    $this->Command("php artisan migrate --seed --force");
                    $this->output->comment("Now we are going to make a user for your panel");
                    $this->setup_first_user();
                    $this->output->comment("Now some final changes and you are good to go");
                    //$this->Command("bash /var/www/pelican/app/Console/Commands/Scripts/add_cronjob.sh");
                    $webserver = $this->choice(__("commands.install.webserver_question"), [
                        "NGINX/Apache",
                        "Rocky Linux NGINX",
                        "Rocky Linux Apache"
                    ]);
                    if ($webserver === "NGINX/Apache") {
                        $this->Command("chown -R www-data:www-data /var/www/pelican/* ");
                    } elseif ($webserver === "Rocky Linux NGINX") {
                        $this->Command("chown -R nginx:nginx /var/www/pelican/* ");
                    } elseif ($webserver === "Rocky Linux Apache") {
                        $this->Command("chown -R apache:apache /var/www/pelican/* ");
                    }
                }
            } elseif ($second_question === __("commands.install.no_second")) {
                $this->info("Please install all dependencies before you continue installing the panel.");
            }
        }
    }

    private function Command($command)
    {
        $output = shell_exec($command);

        echo $output;
    }
    public function setup_settings()
    {
        $this->output->comment('Choose a language for your panel.');
        /*
        $langDirectory = 'lang';
        $files = scandir($langDirectory);
        $languages = array_filter($files, function($item) use ($langDirectory) {
            return is_dir($langDirectory . '/' . $item);
        });
        */
        $languages = $this->isLanguageTranslated();
        if (empty($languages)) {
            $this->output->error('No languages available.');
            return;
        }
        $languages = array_diff($languages, ['.', '..']);
        $this->variables['APP_LOCALE'] = $this->choice('What language do you want to use?', $languages, config('app.locale', 'en'));

        if (empty(config('hashids.salt')) || $this->option('new-salt')) {
            $this->variables['HASHIDS_SALT'] = str_random(20);
        }

        $this->output->comment('Provide the email address that eggs exported by this Panel should be from. This should be a valid email address.');
        $this->variables['APP_SERVICE_AUTHOR'] = $this->option('author') ?? $this->ask(
            'Egg Author Email',
            config('panel.service.author', 'unknown@unknown.com')
        );

        if (!filter_var($this->variables['APP_SERVICE_AUTHOR'], FILTER_VALIDATE_EMAIL)) {
            $this->output->error('The service author email provided is invalid.');

        }

        $this->output->comment('The application URL MUST begin with https:// or http:// depending on if you are using SSL or not. If you do not include the scheme your emails and other content will link to the wrong location.');
        $this->variables['APP_URL'] = $this->option('url') ?? $this->ask(
            'Application URL',
            config('app.url', 'https://example.com')
        );

        $this->output->comment('The timezone should match one of PHP\'s supported timezones. If you are unsure, please reference https://php.net/manual/en/timezones.php.');
        $this->variables['APP_TIMEZONE'] = $this->option('timezone') ?? $this->anticipate(
            'Application Timezone',
            \DateTimeZone::listIdentifiers(),
            config('app.timezone')
        );

        $selected = config('cache.default', 'file');
        $this->variables['CACHE_STORE'] = $this->option('cache') ?? $this->choice(
            'Cache Driver',
            self::CACHE_DRIVERS,
            array_key_exists($selected, self::CACHE_DRIVERS) ? $selected : null
        );

        $selected = config('session.driver', 'file');
        $this->variables['SESSION_DRIVER'] = $this->option('session') ?? $this->choice(
            'Session Driver',
            self::SESSION_DRIVERS,
            array_key_exists($selected, self::SESSION_DRIVERS) ? $selected : null
        );

        $selected = config('queue.default', 'sync');
        $this->variables['QUEUE_CONNECTION'] = $this->option('queue') ?? $this->choice(
            'Queue Driver',
            self::QUEUE_DRIVERS,
            array_key_exists($selected, self::QUEUE_DRIVERS) ? $selected : null
        );

        if (!is_null($this->option('settings-ui'))) {
            $this->variables['APP_ENVIRONMENT_ONLY'] = $this->option('settings-ui') == 'true' ? 'false' : 'true';
        } else {
            $this->variables['APP_ENVIRONMENT_ONLY'] = $this->confirm('Enable UI based settings editor?', true) ? 'false' : 'true';
        }

        if (str_starts_with($this->variables['APP_URL'], 'https://')) {
            $this->variables['SESSION_SECURE_COOKIE'] = 'true';
        }

        $this->checkForRedis();
        $this->writeToEnvironment($this->variables);

        $this->info($this->console->output());

    }

    private function setup_database()
    {
        $this->output->note('It is highly recommended to not use "localhost" as your database host as we have seen frequent socket connection issues. If you want to use a local connection you should be using "127.0.0.1".');
        $this->variables['DB_HOST'] = $this->option('host') ?? $this->ask(
            'Database Host',
            config('database.connections.mysql.host', '127.0.0.1')
        );

        $this->variables['DB_PORT'] = $this->option('port') ?? $this->ask(
            'Database Port',
            config('database.connections.mysql.port', 3306)
        );

        $this->variables['DB_DATABASE'] = $this->option('database') ?? $this->ask(
            'Database Name',
            config('database.connections.mysql.database', 'panel')
        );

        $this->output->note('Using the "root" account for MySQL connections is not only highly frowned upon, it is also not allowed by this application. You\'ll need to have created a MySQL user for this software.');
        $this->variables['DB_USERNAME'] = $this->option('username') ?? $this->ask(
            'Database Username',
            config('database.connections.mysql.username', 'panel')
        );

        $askForMySQLPassword = true;
        if (!empty(config('database.connections.mysql.password')) && $this->input->isInteractive()) {
            $this->variables['DB_PASSWORD'] = config('database.connections.mysql.password');
            $askForMySQLPassword = $this->confirm('It appears you already have a MySQL connection password defined, would you like to change it?');
        }

        if ($askForMySQLPassword) {
            $this->variables['DB_PASSWORD'] = $this->option('password') ?? $this->secret('Database Password');
        }

        try {
            $this->testMySQLConnection();
        } catch (\PDOException $exception) {
            $this->output->error(sprintf('Unable to connect to the MySQL server using the provided credentials. The error returned was "%s".', $exception->getMessage()));
            $this->output->error('Your connection credentials have NOT been saved. You will need to provide valid connection information before proceeding.');

            if ($this->confirm('Go back and try again?')) {
                $this->database->disconnect('_panel_command_test');

                return $this->handle();
            }
        }

        $this->writeToEnvironment($this->variables);

        $this->info($this->console->output());

    }
    /**
     * Handle command request to create a new user.
     *
     * @throws Exception
     * @throws \App\Exceptions\Model\DataValidationException
     */
    private function setup_first_user()
    {
        try {
            DB::select('select 1 where 1');
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }

        $root_admin = $this->option('admin') ?? $this->confirm(trans('command/messages.user.ask_admin'));
        $email = $this->option('email') ?? $this->ask(trans('command/messages.user.ask_email'));
        $username = $this->option('username') ?? $this->ask(trans('command/messages.user.ask_username'));
        $name_first = $this->option('name-first') ?? $this->ask(trans('command/messages.user.ask_name_first'));
        $name_last = $this->option('name-last') ?? $this->ask(trans('command/messages.user.ask_name_last'));

        if (is_null($password = $this->option('password')) && !$this->option('no-password')) {
            $this->warn(trans('command/messages.user.ask_password_help'));
            $this->line(trans('command/messages.user.ask_password_tip'));
            $password = $this->secret(trans('command/messages.user.ask_password'));
        }

        $user = $this->creationService->handle(compact('email', 'username', 'name_first', 'name_last', 'password', 'root_admin'));
        $this->table(['Field', 'Value'], [
            ['UUID', $user->uuid],
            ['Email', $user->email],
            ['Username', $user->username],
            ['Name', $user->name],
            ['Admin', $user->root_admin ? 'Yes' : 'No'],
        ]);
    }
    public const CACHE_DRIVERS = [
        'redis' => 'Redis',
        'memcached' => 'Memcached',
        'file' => 'Filesystem (recommended)',
    ];

    public const SESSION_DRIVERS = [
        'redis' => 'Redis',
        'memcached' => 'Memcached',
        'database' => 'MySQL Database',
        'file' => 'Filesystem (recommended)',
        'cookie' => 'Cookie',
    ];

    public const QUEUE_DRIVERS = [
        'redis' => 'Redis',
        'database' => 'MySQL Database',
        'sync' => 'Sync (recommended)',
    ];

    private function checkForRedis()
    {
        $items = collect($this->variables)->filter(function ($item) {
            return $item === 'redis';
        });

        // Redis was not selected, no need to continue.
        if (count($items) === 0) {
            return;
        }

        $this->output->note('You\'ve selected the Redis driver for one or more options, please provide valid connection information below. In most cases you can use the defaults provided unless you have modified your setup.');
        $this->variables['REDIS_HOST'] = $this->option('redis-host') ?? $this->ask(
            'Redis Host',
            config('database.redis.default.host')
        );

        $askForRedisPassword = true;
        if (!empty(config('database.redis.default.password'))) {
            $this->variables['REDIS_PASSWORD'] = config('database.redis.default.password');
            $askForRedisPassword = $this->confirm('It seems a password is already defined for Redis, would you like to change it?');
        }

        if ($askForRedisPassword) {
            $this->output->comment('By default a Redis server instance has no password as it is running locally and inaccessible to the outside world. If this is the case, simply hit enter without entering a value.');
            $this->variables['REDIS_PASSWORD'] = $this->option('redis-pass') ?? $this->output->askHidden(
                'Redis Password'
            );
        }

        if (empty($this->variables['REDIS_PASSWORD'])) {
            $this->variables['REDIS_PASSWORD'] = 'null';
        }

        $this->variables['REDIS_PORT'] = $this->option('redis-port') ?? $this->ask(
            'Redis Port',
            config('database.redis.default.port')
        );
    }
    private function testMySQLConnection()
    {
        config()->set('database.connections._panel_command_test', [
            'driver' => 'mysql',
            'host' => $this->variables['DB_HOST'],
            'port' => $this->variables['DB_PORT'],
            'database' => $this->variables['DB_DATABASE'],
            'username' => $this->variables['DB_USERNAME'],
            'password' => $this->variables['DB_PASSWORD'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'strict' => true,
        ]);

        $this->database->connection('_panel_command_test')->getPdo();
    }
}

