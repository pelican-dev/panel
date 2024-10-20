<?php

namespace App\Services\Servers;

use App\Models\Objects\Endpoint;
use App\Models\Server;

class StartupCommandService
{
    /**
     * Generates a startup command for a given server instance.
     */
    public function handle(Server $server, bool $hideAllValues = false): string
    {
        $endpoint = $server->getPrimaryEndpoint();

        $find = ['{{SERVER_MEMORY}}', '{{SERVER_IP}}', '{{SERVER_PORT}}'];
        $replace = [$server->memory, $endpoint->ip ?? Endpoint::INADDR_ANY, $endpoint->port ?? ''];

        foreach ($server->variables as $variable) {
            $find[] = '{{' . $variable->env_variable . '}}';
            $replace[] = ($variable->user_viewable && !$hideAllValues) ? ($variable->server_value ?? $variable->default_value) : '[hidden]';
        }

        return str_replace($find, $replace, $server->startup);
    }
}
