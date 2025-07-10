<?php

namespace App\Rules;

use App\Models\Allocation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class UniquePortOnSameNetwork implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     */
    protected array $data = [];

    /**
     * The ID of the allocation being updated (if any).
     */
    protected ?int $ignoreId = null;

    /**
     * Create a new rule instance.
     */
    public function __construct(?int $ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    /**
     * Set the data under validation.
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $nodeId = $this->data['node_id'] ?? null;
        $ip = $this->data['ip'] ?? null;

        if (!$nodeId || !$ip) {
            return;
        }

        // If we're updating an existing allocation, we need to modify the conflict check
        if ($this->ignoreId) {
            // For updates, we need to check if there's a conflict excluding the current allocation
            $conflictingAllocation = Allocation::query()
                ->where('port', (int) $value)
                ->where('node_id', '!=', (int) $nodeId)
                ->where('id', '!=', $this->ignoreId)
                ->get()
                ->first(function ($allocation) use ($ip) {
                    return Allocation::areIpsOnSameNetwork($ip, $allocation->ip);
                });

            if ($conflictingAllocation) {
                $fail(trans('exceptions.allocations.port_conflict_same_network', [
                    'port' => $value,
                    'ip' => $ip,
                    'node_id' => $conflictingAllocation->node_id,
                ]));
            }
        } else {
            // For new allocations, use the existing method
            if (Allocation::hasPortConflictOnSameNetwork($ip, (int) $value, (int) $nodeId)) {
                $conflictingAllocation = Allocation::getPortConflictOnSameNetwork($ip, (int) $value, (int) $nodeId);
                
                $fail(trans('exceptions.allocations.port_conflict_same_network', [
                    'port' => $value,
                    'ip' => $ip,
                    'node_id' => $conflictingAllocation->node_id,
                ]));
            }
        }
    }
} 