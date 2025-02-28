<?php

namespace App\Repositories\Daemon;

use App\Models\Node;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Webmozart\Assert\Assert;
use App\Models\Server;

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

        return Http::daemon($this->node, $headers);
    }
}
