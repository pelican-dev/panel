<?php

namespace App\Http\Requests\Api\Application\Webhooks;

use App\Enums\WebhookScope;
use App\Facades\WebhookTypes;
use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\WebhookConfiguration;
use App\Services\Acl\Api\AdminAcl;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreWebhookRequest extends ApplicationApiRequest
{
    protected ?string $resource = WebhookConfiguration::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    /** @return array<string, string|array<string|\Stringable|ValidationRule>> */
    public function rules(): array
    {
        return array_merge($this->baseRules(), $this->payloadRules());
    }

    /**
     * Rules the selected type declares for its own payload, namespaced under `payload`.
     *
     * @return array<string, mixed>
     */
    protected function payloadRules(): array
    {
        $schema = WebhookTypes::get($this->resolveType());

        if (!$schema) {
            return [];
        }

        $rules = [];
        foreach ($schema->getPayloadRules() as $key => $rule) {
            $rules["payload.$key"] = $rule;
        }

        return $rules;
    }

    protected function resolveType(): ?string
    {
        if ($this->filled('type')) {
            return $this->input('type');
        }

        return WebhookTypes::detect($this->input('endpoint'));
    }

    /** @return array<string, string|array<string|\Stringable|ValidationRule>> */
    protected function baseRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:191'],
            'endpoint' => ['required', 'string', 'url', 'max:191'],
            // Types are registered at runtime, so the allowed values depend on which plugins are loaded
            'type' => ['sometimes', 'string', Rule::in(array_keys(WebhookTypes::getOptions()))],
            'scope' => ['sometimes', Rule::enum(WebhookScope::class)],
            'server_id' => ['nullable', 'integer', 'exists:servers,id'],
            'events' => ['required', 'array', 'min:1'],
            'events.*' => ['required', 'string'],
            'payload' => ['nullable', 'array'],
            'headers' => ['nullable', 'array'],
            'headers.*' => ['string'],
        ];
    }

    /**
     * A server scoped webhook needs a server, and the events it may listen to differ
     * from the global ones, so both are checked against the resolved scope.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $scope = $this->resolveScope();

            if ($scope === WebhookScope::Server && !$this->hasServer()) {
                $validator->errors()->add('server_id', trans('validation.required', ['attribute' => 'server id']));
            }

            $allowed = array_keys(WebhookConfiguration::filamentCheckboxList($scope));

            foreach ((array) $this->input('events', []) as $index => $event) {
                if (!in_array($event, $allowed, true)) {
                    $validator->errors()->add("events.$index", trans('validation.in', ['attribute' => "events.$index"]));
                }
            }
        });
    }

    protected function resolveScope(): WebhookScope
    {
        if ($this->filled('scope')) {
            return WebhookScope::tryFrom($this->input('scope')) ?? WebhookScope::Global;
        }

        return $this->hasServer() ? WebhookScope::Server : WebhookScope::Global;
    }

    protected function hasServer(): bool
    {
        return $this->filled('server_id');
    }
}
