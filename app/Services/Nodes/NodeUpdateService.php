<?php

namespace App\Services\Nodes;

use Illuminate\Support\Str;
use App\Models\Node;
use Illuminate\Database\ConnectionInterface;
use App\Repositories\Daemon\DaemonConfigurationRepository;
use App\Exceptions\Http\Connection\DaemonConnectionException;
use App\Exceptions\Service\Node\ConfigurationNotPersistedException;

class NodeUpdateService
{
    /**
     * NodeUpdateService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private DaemonConfigurationRepository $configurationRepository,
    ) {
    }

    /**
     * Update the configuration values for a given node on the machine.
     *
     * @throws \Throwable
     */
    public function handle(Node $node, array $data, bool $resetToken = false): Node
    {
        if ($resetToken) {
            $data['daemon_token'] = Str::random(Node::DAEMON_TOKEN_LENGTH);
            $data['daemon_token_id'] = Str::random(Node::DAEMON_TOKEN_ID_LENGTH);
        }

        [$node, $exception] = $this->connection->transaction(function () use ($data, $node) {
            $node->forceFill($data)->save();
            try {
                $this->configurationRepository->setNode($node)->update($node);
            } catch (DaemonConnectionException $exception) {
                logger()->warning($exception, ['node_id' => $node->id]);

                // Never actually throw these exceptions up the stack. If we were able to change the settings
                // but something went wrong with daemon we just want to store the update and let the user manually
                // make changes as needed.
                //
                // This avoids issues with proxies such as Cloudflare which will see daemon as offline and then
                // inject their own response pages, causing this logic to get fucked up.
                return [$node, true];
            }

            return [$node, false];
        });

        if ($exception) {
            throw new ConfigurationNotPersistedException(trans('exceptions.node.daemon_off_config_updated'));
        }

        return $node;
    }
}
