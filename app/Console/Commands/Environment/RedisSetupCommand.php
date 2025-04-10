<?php

namespace App\Console\Commands\Environment;

use App\Traits\Commands\RequestRedisSettingsTrait;
use App\Traits\EnvironmentWriterTrait;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;

class RedisSetupCommand extends Command
{
    use EnvironmentWriterTrait;
    use RequestRedisSettingsTrait;

    protected $description = 'Configure the Panel to use Redis as cache, queue and session driver.';

    protected $signature = 'p:redis:setup
                            {--redis-host= : Redis host to use for connections.}
                            {--redis-user= : User used to connect to redis.}
                            {--redis-pass= : Password used to connect to redis.}
                            {--redis-port= : Port to connect to redis over.}';

    /**
     * RedisSetupCommand constructor.
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
        $this->variables['CACHE_STORE'] = 'redis';
        $this->variables['QUEUE_CONNECTION'] = 'redis';
        $this->variables['SESSION_DRIVER'] = 'redis';

        $this->requestRedisSettings();

        $this->call('p:environment:queue-service', [
            '--overwrite' => true,
        ]);

        $this->writeToEnvironment($this->variables);

        $this->info($this->console->output());

        return 0;
    }
}
