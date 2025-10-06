<?php

namespace App\Repositories\Daemon;

use App\Models\Node;
use App\Models\Server;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Webmozart\Assert\Assert;

abstract class DaemonRepository
{
    protected ?Server $server;

    protected ?Node $node;

    /**
     * Set the server model this request is stemming from.
     *
     * @return static
     */
    public function setServer(Server $server): self
    {
        $this->server = $server;

        $this->setNode($this->server->node);

        return $this;
    }

    /**
     * Set the node model this request is stemming from.
     */
    public function setNode(Node $node): static
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Return an instance of the Guzzle HTTP Client to be used for requests.
     *
     * @param  array<string, string>  $headers
     */
    public function getHttpClient(array $headers = []): PendingRequest
    {
        Assert::isInstanceOf($this->node, Node::class);

        return Http::daemon($this->node, $headers)->throwIf(fn ($condition) => $this->enforceValidNodeToken($condition));
    }

    protected function enforceValidNodeToken(Response|bool $condition): bool
    {
        if (is_bool($condition)) {
            return $condition;
        }
        if ($condition->clientError()) {
            return false;
        }

        $header = $condition->header('User-Agent');
        if (
            empty($header) ||
            preg_match('/^Pelican Wings\/v(?:\d+\.\d+\.\d+|develop) \(id:(\w*)\)$/', $header, $matches) &&
            array_get($matches, 1, '') !== $this->node->daemon_token_id
        ) {
            throw new ConnectionException($condition->effectiveUri()->__toString() . ' does not match node token_id !');
        }

        return true;
    }
}
