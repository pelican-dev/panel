<?php

namespace App\Console\Commands\Environment;

use App\Traits\Commands\RequestRedisSettingsTrait;
use App\Traits\EnvironmentWriterTrait;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;

class CacheSettingsCommand extends Command
{
    use EnvironmentWriterTrait;
    use RequestRedisSettingsTrait;

    public const CACHE_DRIVERS = [
        'file' => 'Filesystem (default)',
        'database' => 'Database',
        'redis' => 'Redis',
    ];

    protected $description = 'Configure cache settings for the Panel.';

    protected $signature = 'p:environment:cache
                            {--driver= : The cache driver backend to use.}
                            {--redis-host= : Redis host to use for connections.}
                            {--redis-user= : User used to connect to redis.}
                            {--redis-pass= : Password used to connect to redis.}
                            {--redis-port= : Port to connect to redis over.}';

    /**
     * CacheSettingsCommand constructor.
     */
    public function __construct(private Kernel $console)
    {
        parent::__construct();
    }

    /**
     * Handle command execution.
     */
    public function handle(): int
    {
        $selected = config('cache.default', 'file');
        $this->variables['CACHE_STORE'] = $this->option('driver') ?? $this->choice(
            'Cache Driver',
            self::CACHE_DRIVERS,
            array_key_exists($selected, self::CACHE_DRIVERS) ? $selected : null
        );

        if ($this->variables['CACHE_STORE'] === 'redis') {
            $this->requestRedisSettings();

            if (config('queue.default') !== 'sync') {
                $this->call('p:environment:queue-service', [
                    '--overwrite' => true,
                ]);
            }
        }

        $this->writeToEnvironment($this->variables);

        $this->info($this->console->output());

        return 0;
    }
}
