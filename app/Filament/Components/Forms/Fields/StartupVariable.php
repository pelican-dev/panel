<?php

namespace App\Filament\Components\Forms\Fields;

use App\Enums\StartupVariableType;
use App\Models\ServerVariable;
use Closure;
use Filament\Forms\Components\Concerns\HasAffixes;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Support\Str;

class StartupVariable extends Field
{
    use HasAffixes;
    use HasExtraAlpineAttributes;
    use HasExtraInputAttributes;

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

        $this->live(onBlur: true);
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
        $record = $this->getRecord();
        if ($record instanceof ServerVariable) {
            return $record->variable->name;
        }

        return $this->evaluate($this->variableName);
    }

    public function getVariableDesc(): ?string
    {
        $record = $this->getRecord();
        if ($record instanceof ServerVariable) {
            return $record->variable->description;
        }

        return $this->evaluate($this->variableDesc);
    }

    public function getVariableEnv(): ?string
    {
        $record = $this->getRecord();
        if ($record instanceof ServerVariable) {
            return $record->variable->env_variable;
        }

        return $this->evaluate($this->variableEnv);
    }

    public function getVariableDefault(): ?string
    {
        $record = $this->getRecord();
        if ($record instanceof ServerVariable) {
            return $record->variable->default_value;
        }

        return $this->evaluate($this->variableDefault);
    }

    /** @return string[] */
    public function getVariableRules(): array
    {
        $record = $this->getRecord();
        if ($record instanceof ServerVariable) {
            return $record->variable->rules;
        }

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

        if (array_first($rules, fn ($value) => str($value)->startsWith('in:'), false)) {
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

        $inRule = array_first($rules, fn ($value) => str($value)->startsWith('in:'));
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
