<?php

namespace App\Http\Requests\Api\Application\Webhooks;

use App\Enums\WebhookScope;
use App\Models\WebhookConfiguration;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateWebhookRequest extends StoreWebhookRequest
{
    /**
     * Everything is optional on update, so only the fields actually sent are validated.
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
     * Fall back to the scope already stored on the record when the request does not change it.
     */
    protected function resolveScope(): WebhookScope
    {
        if (!$this->filled('scope') && !$this->filled('server_id')) {
            return $this->record()->scope;
        }

        return parent::resolveScope();
    }

    protected function hasServer(): bool
    {
        return parent::hasServer() || $this->record()->server_id !== null;
    }

    /**
     * Fall back to the type already stored on the record, so payload rules still apply
     * when the request only changes the payload.
     */
    protected function resolveType(): ?string
    {
        if (!$this->filled('type') && !$this->filled('endpoint')) {
            return $this->record()->type;
        }

        return parent::resolveType();
    }

    protected function record(): WebhookConfiguration
    {
        return $this->parameter('webhook', WebhookConfiguration::class);
    }
}
