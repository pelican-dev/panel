<?php

namespace App\Services\Eggs;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Server;
use App\Services\Servers\ServerConfigurationStructureService;

class EggConfigurationService
{
    /**
     * EggConfigurationService constructor.
     */
    public function __construct(private ServerConfigurationStructureService $configurationStructureService) {}

    /**
     * Return an Egg file to be used by the Daemon.
     *
     * @return array{
     *     startup: array{done: string[], user_interaction: string[], strip_ansi: bool},
     *     stop: array{type: string, value: string},
     *     configs: array<mixed>
     * }
     */
    public function handle(Server $server): array
    {
        $configs = $this->replacePlaceholders(
            $server,
            json_decode($server->egg->inherit_config_files)
        );

        return [
            'startup' => $this->convertStartupToNewFormat(json_decode($server->egg->inherit_config_startup, true)),
            'stop' => $this->convertStopToNewFormat($server->egg->inherit_config_stop),
            'configs' => $configs,
        ];
    }

    /**
     * Convert the "done" variable into an array if it is not currently one.
     *
     * @param  array{done: string|string[], strip_ansi: bool}  $startup
     * @return array{done: string[], user_interaction: string[], strip_ansi: bool}
     */
    protected function convertStartupToNewFormat(array $startup): array
    {
        $done = Arr::get($startup, 'done');

        return [
            'done' => is_string($done) ? [$done] : $done,
            'user_interaction' => [],
            'strip_ansi' => Arr::get($startup, 'strip_ansi') ?? false,
        ];
    }

    /**
     * Converts a legacy stop string into a new generation stop option for a server.
     *
     * For most eggs, this ends up just being a command sent to the server console, but
     * if the stop command is something starting with a caret (^), it will be converted
     * into the associated kill signal for the instance.
     *
     * @return array{type: string, value: string}
     */
    protected function convertStopToNewFormat(string $stop): array
    {
        if (!Str::startsWith($stop, '^')) {
            return [
                'type' => 'command',
                'value' => $stop,
            ];
        }

        $signal = substr($stop, 1);

        return [
            'type' => 'signal',
            'value' => strtoupper($signal),
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function replacePlaceholders(Server $server, object $configs): array
    {
        // Get the legacy configuration structure for the server so that we
        // can property map the egg placeholders to values.
        $structure = $this->configurationStructureService->handle($server);

        $response = [];
        // Normalize the output of the configuration for the new Daemon to more
        // easily ingest, as well as make things more flexible down the road.
        foreach ($configs as $file => $data) {
            // Try to head off any errors relating to parsing a set of configuration files
            // or other JSON data for the egg. This should probably be blocked at the time
            // of egg creation/update, but it isn't so this check will at least prevent a
            // 500 error which would crash the entire daemon boot process.
            if (!is_object($data) || !isset($data->find)) {
                continue;
            }

            $append = array_merge((array) $data, ['file' => $file, 'replace' => []]);

            foreach ($this->iterate($data->find, $structure) as $find => $replace) {
                if (is_object($replace)) {
                    foreach ($replace as $match => $replaceWith) {
                        $append['replace'][] = [
                            'match' => $find,
                            'if_value' => $match,
                            'replace_with' => $replaceWith,
                        ];
                    }

                    continue;
                }

                $append['replace'][] = [
                    'match' => $find,
                    'replace_with' => $replace,
                ];
            }

            unset($append['find']);

            $response[] = $append;
        }

        return $response;
    }

    /**
     * @param  array{string, mixed}  $structure
     */
    protected function matchAndReplaceKeys(mixed $value, array $structure): mixed
    {
        preg_match_all('/{{(?<key>[\w.-]*)}}/', $value, $matches);

        foreach ($matches['key'] as $key) {
            // Matched something in {{server.X}} format, now replace that with the actual
            // value from the server properties.
            //
            // The Daemon supports server.X, env.X, and config.X placeholders.
            if (!Str::startsWith($key, ['server.', 'env.', 'config.'])) {
                continue;
            }

            // Don't do a replacement on anything that is not a string, we don't want to unintentionally
            // modify the resulting output.
            if (!is_string($value)) {
                continue;
            }

            // We don't want to do anything with config keys since the Daemon will need to handle
            // that. For example, the Spigot egg uses "config.docker.interface" to identify the Docker
            // interface to proxy through, but the Panel would be unaware of that.
            if (Str::startsWith($key, 'config.')) {
                continue;
            }

            // Replace anything starting with "server." with the value out of the server configuration
            // array that used to be created for the old daemon.
            if (Str::startsWith($key, 'server.')) {
                $plucked = Arr::get($structure, preg_replace('/^server\./', '', $key), '');

                $value = str_replace("{{{$key}}}", $plucked, $value);

                continue;
            }

            // Finally, replace anything starting with env. with the expected environment
            // variable from the server configuration.
            $plucked = Arr::get(
                $structure,
                preg_replace('/^env\./', 'environment.', $key),
                ''
            );

            $value = str_replace("{{{$key}}}", $plucked, $value);
        }

        return $value;
    }

    /**
     * Iterates over a set of "find" values for a given file in the parser configuration. If
     * the value of the line match is something iterable, continue iterating, otherwise perform
     * a match & replace.
     *
     * @param  array<mixed>  $structure
     */
    private function iterate(mixed $data, array $structure): mixed
    {
        if (!is_iterable($data) && !is_object($data)) {
            return $data;
        }

        // Remember, in PHP objects are always passed by reference, so if we do not clone this object
        // instance we'll end up making modifications to the object outside the scope of this function
        // which leads to some fun behavior in the parser.
        if (is_array($data)) {
            // Copy the array.
            // NOTE: if the array contains any objects, they will be passed by reference.
            $clone = $data;
        } else {
            $clone = clone $data;
        }
        foreach ($clone as $key => &$value) {
            if (is_iterable($value) || is_object($value)) {
                $value = $this->iterate($value, $structure);

                continue;
            }

            $value = $this->matchAndReplaceKeys($value, $structure);
        }

        return $clone;
    }
}
