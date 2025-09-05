<?php

namespace App\Filament\Components\Forms\Fields;

use App\Enums\StartupVariableType;
use App\Models\ServerVariable;
use Closure;
use Filament\Forms\Components\Concerns\HasAffixes;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Forms\Get;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class StartupVariable extends Field
{
    use HasAffixes;
    use HasExtraAlpineAttributes;
    use HasExtraInputAttributes;
    use HasPlaceholder;

    /** @var view-string */
    protected string $view = 'filament.components.startup-variable';

    protected string|Closure|null $variableName = null;

    protected string|Closure|null $variableDesc = null;

    protected string|Closure|null $variableEnv = null;

    protected string|Closure|null $variableDefault = null;

    /** @var string[]|Closure|null */
    protected array|Closure|null $variableRules = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (StartupVariable $component) => $component->getVariableName());

        $this->prefix(fn (StartupVariable $component) => '{{' . $component->getVariableEnv() . '}}');

        $this->hintIcon('tabler-code');

        $this->hintIconTooltip(fn (StartupVariable $component) => implode('|', $component->getVariableRules()));

        $this->helperText(fn (StartupVariable $component) => !$component->getVariableDesc() ? '—' : $component->getVariableDesc());

        $this->rules(fn (StartupVariable $component) => $component->getVariableRules());

        $this->placeholder(fn (StartupVariable $component) => $component->getVariableDefault());

        $this->live(onBlur: true);
    }

    public function fromForm(): static
    {
        $this->variableName(fn (Get $get) => $get('name'));
        $this->variableDesc(fn (Get $get) => $get('description'));
        $this->variableEnv(fn (Get $get) => $get('env_variable'));
        $this->variableDefault(fn (Get $get) => $get('default_value'));
        $this->variableRules(fn (Get $get) => $get('rules'));

        return $this;
    }

    public function fromRecord(): static
    {
        $this->variableName(fn (ServerVariable $record) => $record->variable->name);
        $this->variableDesc(fn (ServerVariable $record) => $record->variable->description);
        $this->variableEnv(fn (ServerVariable $record) => $record->variable->env_variable);
        $this->variableDefault(fn (ServerVariable $record) => $record->variable->default_value);
        $this->variableRules(fn (ServerVariable $record) => $record->variable->rules);

        return $this;
    }

    public function variableName(string|Closure|null $name): static
    {
        $this->variableName = $name;

        return $this;
    }

    public function variableDesc(string|Closure|null $desc): static
    {
        $this->variableDesc = $desc;

        return $this;
    }

    public function variableEnv(string|Closure|null $envVariable): static
    {
        $this->variableEnv = $envVariable;

        return $this;
    }

    public function variableDefault(string|Closure|null $default): static
    {
        $this->variableDefault = $default;

        return $this;
    }

    /** @param string[]|Closure|null $rules */
    public function variableRules(array|Closure|null $rules): static
    {
        $this->variableRules = $rules;

        return $this;
    }

    public function getVariableName(): ?string
    {
        return $this->evaluate($this->variableName);
    }

    public function getVariableDesc(): ?string
    {
        return $this->evaluate($this->variableDesc);
    }

    public function getVariableEnv(): ?string
    {
        return $this->evaluate($this->variableEnv);
    }

    public function getVariableDefault(): ?string
    {
        return $this->evaluate($this->variableDefault);
    }

    /** @return string[] */
    public function getVariableRules(): array
    {
        return (array) ($this->evaluate($this->variableRules) ?? []);
    }

    public function isRequired(): bool
    {
        $rules = $this->getVariableRules();

        return in_array('required', $rules);
    }

    public function getType(): StartupVariableType
    {
        $rules = $this->getVariableRules();

        if (Arr::first($rules, fn ($value) => str($value)->startsWith('in:'), false)) {
            return StartupVariableType::Select;
        }

        if (in_array('boolean', $rules)) {
            return StartupVariableType::Toggle;
        }

        return StartupVariableType::Text;
    }

    /** @return string[] */
    public function getSelectOptions(): array
    {
        $rules = $this->getVariableRules();

        $inRule = Arr::first($rules, fn ($value) => str($value)->startsWith('in:'));
        if ($inRule) {
            return str($inRule)
                ->after('in:')
                ->explode(',')
                ->each(fn ($value) => Str::trim($value))
                ->all();
        }

        return [];
    }
}
