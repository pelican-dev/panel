<?php

namespace App\Services\Nodes;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use App\Models\Node;

class NodeCreationService
{
    /**
     * Create a new node on the panel.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function handle(array $data): Node
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['daemon_token'] = Str::random(Node::DAEMON_TOKEN_LENGTH);
        $data['daemon_token_id'] = Str::random(Node::DAEMON_TOKEN_ID_LENGTH);

        return Node::query()->create($data);
    }
}
