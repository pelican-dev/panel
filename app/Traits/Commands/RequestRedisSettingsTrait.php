<?php

namespace App\Traits\Commands;

trait RequestRedisSettingsTrait
{
    /** @var array<string, mixed> */
    protected array $variables;

    protected function requestRedisSettings(): void
    {
        $this->output->note(trans('commands.appsettings.redis.note'));
        $this->variables['REDIS_HOST'] = $this->option('redis-host') ?? $this->ask(
            trans('strings.appsettings.redis.title_field'),
            config('database.redis.default.host')
        );

        $askForRedisUser = true;
        $askForRedisPassword = true;

        if (!empty(config('database.redis.default.user'))) {
            $this->variables['REDIS_USERNAME'] = config('database.redis.default.user');
            $askForRedisUser = $this->confirm(trans('commands.appsettings.redis.confirm', ['field' => 'user']));
        }
        if (!empty(config('database.redis.default.password'))) {
            $this->variables['REDIS_PASSWORD'] = config('database.redis.default.password');
            $askForRedisPassword = $this->confirm(trans('commands.appsettings.redis.confirm', ['field' => 'password']));
        }

        if ($askForRedisUser) {
            $this->output->comment(trans('commands.appsettings.redis.comment'));
            $this->variables['REDIS_USERNAME'] = $this->option('redis-user') ?? $this->output->askHidden(
                trans('strings.appsettings.redis.user_field')
            );
        }
        if ($askForRedisPassword) {
            $this->output->comment(trans('commands.appsettings.redis.comment'));
            $this->variables['REDIS_PASSWORD'] = $this->option('redis-pass') ?? $this->output->askHidden(
                trans('strings.appsettings.redis.password_field')
            );
        }

        if (empty($this->variables['REDIS_USERNAME'])) {
            $this->variables['REDIS_USERNAME'] = 'null';
        }
        if (empty($this->variables['REDIS_PASSWORD'])) {
            $this->variables['REDIS_PASSWORD'] = 'null';
        }

        $this->variables['REDIS_PORT'] = $this->option('redis-port') ?? $this->ask(
            trans('strings.appsettings.redis.port_field'),
            config('database.redis.default.port')
        );
    }
}
