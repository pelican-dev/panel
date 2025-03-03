<?php

namespace App\Http\Requests\Api\Application\Nodes;

use App\Models\Node;

class UpdateNodeRequest extends StoreNodeRequest
{
    /**
     * @param  array<string, string|string[]>|null  $rules
     * @return array<string, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        /** @var Node $node */
        $node = $this->route()->parameter('node');

        return parent::rules(Node::getRulesForUpdate($node));
    }
}
