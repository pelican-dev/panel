<?php

namespace App\Services\Servers;

use App\Models\Server;
use App\Models\EggVariable;

class EnvironmentService
{
    /** @var array<array-key, callable> */
    private array $additional = [];

    /**
     * Dynamically configure additional environment variables to be assigned with a specific server.
     */
    public function setEnvironmentKey(string $key, callable $closure): void
    {
        $this->additional[$key] = $closure;
    }

    /**
     * Return the dynamically added additional keys.
     *
     * @return array<array-key, callable>
     */
    public function getEnvironmentKeys(): array
    {
        return $this->additional;
    }

    /**
     * Take all the environment variables configured for this server and return
     * them in an easy to process format.
     *
     * @return array<array-key, mixed>
     */
    public function handle(Server $server): array
    {
        $variables = $server->variables->toBase()->mapWithKeys(function (EggVariable $variable) {
            return [$variable->env_variable => $variable->server_value ?? $variable->default_value];
        });

        // Process environment variables defined in this file. This is done first
        // in order to allow run-time and config defined variables to take
        // priority over built-in values.
        foreach ($this->getEnvironmentMappings() as $key => $object) {
            $variables->put($key, object_get($server, $object));
        }

        // Process variables set in the configuration file.
        foreach (config('panel.environment_variables', []) as $key => $object) {
            $variables->put(
                $key,
                is_callable($object) ? call_user_func($object, $server) : object_get($server, $object)
            );
        }

        // Process dynamically included environment variables.
        foreach ($this->additional as $key => $closure) {
            $variables->put($key, call_user_func($closure, $server));
        }

        return $variables->toArray();
    }

    /**
     * Return a mapping of Panel default environment variables.
     *
     * @return array<array-key, string>
     */
    private function getEnvironmentMappings(): array
    {
        return [
            'STARTUP' => 'startup',
            'P_SERVER_UUID' => 'uuid',
        ];
    }
}
