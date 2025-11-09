<?php

namespace App\Services\Nodes;

use App\Exceptions\Service\Node\ConfigurationNotPersistedException;
use App\Models\Node;
use App\Repositories\Daemon\DaemonSystemRepository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;
use Throwable;

class NodeUpdateService
{
    /**
     * NodeUpdateService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private DaemonSystemRepository $configurationRepository,
    ) {}

    /**
     * Update the configuration values for a given node on the machine.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws Throwable
     */
    public function handle(Node $node, array $data, bool $resetToken = false): Node
    {
        $data['id'] = $node->id;

        if ($resetToken) {
            $data['daemon_token'] = Str::random(Node::DAEMON_TOKEN_LENGTH);
            $data['daemon_token_id'] = Str::random(Node::DAEMON_TOKEN_ID_LENGTH);
        }

        [$updated, $exception] = $this->connection->transaction(function () use ($data, $node) {
            /** @var Node $updated */
            $updated = $node->replicate();
            $updated->exists = true;
            $updated->forceFill($data)->save();
            try {
                $node->fqdn = $updated->fqdn;

                $this->configurationRepository->setNode($node)->update($updated);
            } catch (ConnectionException $exception) {
                logger()->warning($exception, ['node_id' => $node->id]);

                // Never actually throw these exceptions up the stack. If we were able to change the settings
                // but something went wrong with daemon we just want to store the update and let the user manually
                // make changes as needed.
                //
                // This avoids issues with proxies such as Cloudflare which will see daemon as offline and then
                // inject their own response pages, causing this logic to get fucked up.
                return [$updated, true];
            }

            return [$updated, false];
        });

        if ($exception) {
            throw new ConfigurationNotPersistedException(trans('exceptions.node.daemon_off_config_updated'));
        }

        return $updated;
    }
}
