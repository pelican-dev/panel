<?php

namespace App\Http\Requests\Api\Application\Webhooks;

use App\Enums\WebhookScope;
use App\Facades\WebhookTypes;
use App\Models\WebhookConfiguration;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateWebhookRequest extends StoreWebhookRequest
{
    /**
     * A PATCH only sends what it changes, so the fields it omits must not be required.
     *
     * @return array<string, string|array<string|\Stringable|ValidationRule>>
     */
    public function rules(): array
    {
        $rules = parent::rules();

        foreach (['name', 'endpoint', 'events'] as $field) {
            $rules[$field] = array_values(array_map(
                fn ($rule) => $rule === 'required' ? 'sometimes' : $rule,
                $rules[$field]
            ));
        }

        return $rules;
    }

    /**
     * A type may require fields inside its payload, but that must not block an update
     * that only changes another attribute, so the rules apply only when a payload is sent.
     *
     * @return array<string, mixed>
     */
    protected function payloadRules(): array
    {
        return $this->has('payload') ? parent::payloadRules() : [];
    }

    /**
     * Assigning a server implies the server scope, but clearing one does not imply the
     * global scope: the stored scope is kept so the request is rejected rather than
     * silently leaving a server scoped webhook with no server and unusable events.
     */
    protected function resolveScope(): WebhookScope
    {
        if ($scope = $this->scalarInput('scope')) {
            return WebhookScope::tryFrom($scope) ?? WebhookScope::Global;
        }

        if ($this->filled('server_id')) {
            return WebhookScope::Server;
        }

        return $this->record()->scope;
    }

    /**
     * An explicit `server_id: null` is a deliberate unassign, so it must not silently
     * fall back to the server already stored on the record.
     */
    protected function hasServer(): bool
    {
        if ($this->has('server_id')) {
            return parent::hasServer();
        }

        return $this->record()->server_id !== null;
    }

    /**
     * Fall back to the type already stored on the record, so payload rules still apply
     * when the request only changes the payload.
     */
    protected function resolveType(): ?string
    {
        if (!$this->has('type') && !$this->has('endpoint')) {
            return $this->record()->type;
        }

        return parent::resolveType();
    }

    /**
     * Scope and type are validated against values the request only implies, so those same
     * values are persisted, otherwise a record could be saved in a state its own
     * validation would reject.
     *
     * @return array<string, mixed>
     */
    public function resolvedAttributes(): array
    {
        $data = $this->validated();

        if (array_key_exists('server_id', $data) && !array_key_exists('scope', $data)) {
            $data['scope'] = $this->resolveScope();
        }

        if (array_key_exists('endpoint', $data) && !array_key_exists('type', $data)) {
            $data['type'] = WebhookTypes::detectFor($data['endpoint'], $this->record()->type);
        }

        return $data;
    }

    protected function record(): WebhookConfiguration
    {
        return $this->parameter('webhook', WebhookConfiguration::class);
    }
}
