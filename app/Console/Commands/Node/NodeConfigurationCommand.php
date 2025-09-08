<?php

namespace App\Console\Commands\Node;

use App\Models\Node;
use Illuminate\Console\Command;

class NodeConfigurationCommand extends Command
{
    protected $signature = 'p:node:configuration
                            {node : The ID or UUID of the node to return the configuration for.}
                            {--format=yaml : The output format. Options are "yaml" and "json".}';

    protected $description = 'Displays the configuration for the specified node.';

    public function handle(): int
    {
        $column = ctype_digit((string) $this->argument('node')) ? 'id' : 'uuid';

        /** @var Node $node */
        $node = Node::query()->where($column, $this->argument('node'))->firstOr(function () {
            $this->error(trans('commands.node_config.error_not_exist'));

            exit(1);
        });

        $format = $this->option('format');
        if (!in_array($format, ['yaml', 'yml', 'json'])) {
            $this->error(trans('commands.node_config.error_invalid_format'));

            return 1;
        }

        if ($format === 'json') {
            $this->output->write($node->getJsonConfiguration(true));
        } else {
            $this->output->write($node->getYamlConfiguration());
        }

        $this->output->newLine();

        return 0;
    }
}
