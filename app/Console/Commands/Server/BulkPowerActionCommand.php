<?php

namespace App\Console\Commands\Server;

use App\Models\Server;
use App\Repositories\Daemon\DaemonServerRepository;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\ValidationException;

class BulkPowerActionCommand extends Command
{
    protected $signature = 'p:server:bulk-power
                            {action : The action to perform (start, stop, restart, kill)}
                            {--servers= : A comma separated list of servers.}
                            {--nodes= : A comma separated list of nodes.}';

    protected $description = 'Perform bulk power management on large groupings of servers or nodes at once.';

    public function handle(DaemonServerRepository $serverRepository, ValidatorFactory $validator): void
    {
        $action = $this->argument('action');
        $nodes = empty($this->option('nodes')) ? [] : explode(',', $this->option('nodes'));
        $servers = empty($this->option('servers')) ? [] : explode(',', $this->option('servers'));

        $validator = $validator->make([
            'action' => $action,
            'nodes' => $nodes,
            'servers' => $servers,
        ], [
            'action' => 'string|in:start,stop,kill,restart',
            'nodes' => 'array',
            'nodes.*' => 'integer|min:1',
            'servers' => 'array',
            'servers.*' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->all() as $message) {
                $this->output->error($message);
            }

            throw new ValidationException($validator);
        }

        $count = $this->getQueryBuilder($servers, $nodes)->count();
        if (!$this->confirm(trans('command/messages.server.power.confirm', ['action' => $action, 'count' => $count])) && $this->input->isInteractive()) {
            return;
        }

        $bar = $this->output->createProgressBar($count);

        $this->getQueryBuilder($servers, $nodes)->get()->each(function ($server, int $index) use ($action, $serverRepository, &$bar): mixed {
            $bar->clear();

            if (!$server instanceof Server) {
                return null;
            }

            try {
                $serverRepository->setServer($server)->power($action);
            } catch (Exception $exception) {
                $this->output->error(trans('command/messages.server.power.action_failed', [
                    'name' => $server->name,
                    'id' => $server->id,
                    'node' => $server->node->name,
                    'message' => $exception->getMessage(),
                ]));
            }

            $bar->advance();
            $bar->display();

            return null;
        });

        $this->line('');
    }

    /**
     * Returns the query builder instance that will return the servers that should be affected.
     *
     * @param  string[]|int[]  $servers
     * @param  string[]|int[]  $nodes
     */
    protected function getQueryBuilder(array $servers, array $nodes): Builder
    {
        $instance = Server::query()->whereNull('status');

        if (!empty($nodes) && !empty($servers)) {
            $instance->whereIn('id', $servers)->orWhereIn('node_id', $nodes);
        } elseif (empty($nodes) && !empty($servers)) {
            $instance->whereIn('id', $servers);
        } elseif (!empty($nodes)) {
            $instance->whereIn('node_id', $nodes);
        }

        return $instance->with('node');
    }
}
