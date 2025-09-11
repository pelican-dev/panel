<?php

namespace App\Http\Requests\Api\Remote;

use App\Models\Node;
use App\Models\Server;
use Illuminate\Foundation\Http\FormRequest;

class ServerRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Node $node */
        $node = $this->attributes->get('node');

        /** @var ?Server $server */
        $server = $this->route()->parameter('server');

        if ($server) {
            if ($server->transfer) {
                return $server->transfer->old_node === $node->id || $server->transfer->new_node === $node->id;
            }

            return $server->node_id === $node->id;
        }

        return false;
    }
}
