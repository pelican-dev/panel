<?php

namespace App\Console\Commands\Environment;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use App\Traits\Commands\EnvironmentWriterTrait;

class AppSettingsCommand extends Command
{
    use EnvironmentWriterTrait;
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

    protected $description = 'Configure basic environment settings for the Panel.';

    protected $signature = 'p:environment:setup
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
                            {--settings-ui= : Enable or disable the settings UI.}';

    protected array $variables = [];

    /**
     * AppSettingsCommand constructor.
     */
    public function __construct(private Kernel $console)
    {
        parent::__construct();
    }

    /**
     * Handle command execution.
     *
     * @throws \App\Exceptions\PanelException
     */
    public function handle(): int
    {

        if (empty(config('hashids.salt')) || $this->option('new-salt')) {
            $this->variables['HASHIDS_SALT'] = str_random(20);
        }

        $this->output->comment(__('commands.appsettings.comment.author'));
        $this->variables['APP_SERVICE_AUTHOR'] = $this->option('author') ?? $this->ask(
            'Egg Author Email',
            config('panel.service.author', 'unknown@unknown.com')
        );

        if (!filter_var($this->variables['APP_SERVICE_AUTHOR'], FILTER_VALIDATE_EMAIL)) {
            $this->output->error('The service author email provided is invalid.');

            return 1;
        }

        $this->output->comment(__('commands.appsettings.comment.url'));
        $this->variables['APP_URL'] = $this->option('url') ?? $this->ask(
            'Application URL',
            config('app.url', 'https://example.com')
        );

        $this->output->comment(__('commands.appsettings.comment.timezone'));
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
            $this->variables['APP_ENVIRONMENT_ONLY'] = $this->confirm(__('commands.appsettings.comment.settings_ui'), true) ? 'false' : 'true';
        }

        // Make sure session cookies are set as "secure" when using HTTPS
        if (str_starts_with($this->variables['APP_URL'], 'https://')) {
            $this->variables['SESSION_SECURE_COOKIE'] = 'true';
        }

        $this->checkForRedis();
        $this->writeToEnvironment($this->variables);

        $this->info($this->console->output());

        return 0;
    }

    /**
     * Check if redis is selected, if so, request connection details and verify them.
     */
    private function checkForRedis()
    {
        $items = collect($this->variables)->filter(function ($item) {
            return $item === 'redis';
        });

        // Redis was not selected, no need to continue.
        if (count($items) === 0) {
            return;
        }

        $this->output->note(__('commands.appsettings.redis.note'));
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
            $this->output->comment(__('commands.appsettings.redis.comment'));
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
}
