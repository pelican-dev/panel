<?php

namespace App\Console\Commands\Environment;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use App\Traits\Commands\EnvironmentWriterTrait;
use Illuminate\Support\Facades\Artisan;

class AppSettingsCommand extends Command
{
    use EnvironmentWriterTrait;

    public const CACHE_DRIVERS = [
        'file' => 'Filesystem (recommended)',
        'redis' => 'Redis',
    ];

    public const SESSION_DRIVERS = [
        'file' => 'Filesystem (recommended)',
        'redis' => 'Redis',
        'database' => 'Database',
        'cookie' => 'Cookie',
    ];

    public const QUEUE_DRIVERS = [
        'database' => 'Database (recommended)',
        'redis' => 'Redis',
        'sync' => 'Synchronous',
    ];

    protected $description = 'Configure basic environment settings for the Panel.';

    protected $signature = 'p:environment:setup
                            {--url= : The URL that this Panel is running on.}
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
        $this->variables['APP_TIMEZONE'] = 'UTC';

        $this->output->comment(__('commands.appsettings.comment.url'));
        $this->variables['APP_URL'] = $this->option('url') ?? $this->ask(
            'Application URL',
            config('app.url', 'https://example.com')
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

        $selected = config('queue.default', 'database');
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

        $redisUsed = count(collect($this->variables)->filter(function ($item) {
            return $item === 'redis';
        })) !== 0;

        if ($redisUsed) {
            $this->requestRedisSettings();
        }

        $path = base_path('.env');
        if (!file_exists($path)) {
            copy($path . '.example', $path);
        }

        $this->writeToEnvironment($this->variables);

        if (!config('app.key')) {
            Artisan::call('key:generate');
        }

        if ($this->variables['QUEUE_CONNECTION'] !== 'sync') {
            $this->call('p:environment:queue-service', [
                '--use-redis' => $redisUsed,
            ]);
        }

        $this->info($this->console->output());

        return 0;
    }

    /**
     * Request redis connection details and verify them.
     */
    private function requestRedisSettings(): void
    {
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
