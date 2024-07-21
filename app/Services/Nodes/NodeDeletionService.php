<?php

namespace App\Services\Nodes;

use App\Models\Node;
use Illuminate\Contracts\Translation\Translator;
use App\Exceptions\Service\HasActiveServersException;

class NodeDeletionService
{
    /**
     * NodeDeletionService constructor.
     */
    public function __construct(
        protected Translator $translator
    ) {}

    /**
     * Delete a node from the panel if no servers are attached to it.
     *
     * @throws HasActiveServersException
     */
    public function handle(int|Node $node): int
    {
        if (is_int($node)) {
            $node = Node::findOrFail($node);
        }

        if ($node->servers()->count() > 0) {
            throw new HasActiveServersException($this->translator->get('exceptions.node.servers_attached'));
        }

        return (int) $node->delete();
    }
}
