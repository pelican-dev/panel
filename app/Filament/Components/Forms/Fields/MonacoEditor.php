<?php

namespace App\Filament\Components\Forms\Fields;

use App\Enums\EditorLanguages;
use Closure;
use Exception;
use Filament\Forms\Components\Field;

class MonacoEditor extends Field
{
    public bool|Closure $showLoader = true;

    public bool|Closure $automaticLayout = true;

    public int|Closure $lineNumbersMinChars = 3;

    public string|Closure $fontSize = '15px';

    public EditorLanguages|Closure $language = EditorLanguages::html;

    public string|Closure $theme = 'blackboard';

    protected string $view = 'filament.components.monaco-editor';

    protected function setUp(): void
    {
        $this->showLoader = config('monaco-editor.general.show-loader');
        $this->fontSize = config('monaco-editor.general.font-size');
        $this->lineNumbersMinChars = config('monaco-editor.general.line-numbers-min-chars');
        $this->automaticLayout = config('monaco-editor.general.automatic-layout');
        $this->theme = config('monaco-editor.general.default-theme');
    }

    public function editorTheme(): string
    {
        $theme = $this->evaluate($this->theme);

        if (!isset(config('monaco-editor.themes')[$theme])) {
            throw new Exception("Theme {$theme} not found in config file.");
        }

        return json_encode([
            'base' => config("monaco-editor.themes.{$theme}.base"),
            'inherit' => config("monaco-editor.themes.{$theme}.inherit"),
            'rules' => config("monaco-editor.themes.{$theme}.rules"),
            'colors' => config("monaco-editor.themes.{$theme}.colors"),
        ], JSON_THROW_ON_ERROR);
    }

    public function language(EditorLanguages|Closure $lang = EditorLanguages::html): static
    {
        $this->language = $lang;

        return $this;
    }

    public function showLoader(bool|Closure $condition = true): static
    {
        $this->showLoader = $condition;

        return $this;
    }

    public function hideLoader(): static
    {
        $this->showLoader = false;

        return $this;
    }

    public function fontSize(string|Closure $size = '15px'): static
    {
        $this->fontSize = $size;

        return $this;
    }

    public function lineNumbersMinChars(int|Closure $value = 3): static
    {
        $this->lineNumbersMinChars = $value;

        return $this;
    }

    public function automaticLayout(bool|Closure $condition = true): static
    {
        $this->automaticLayout = $condition;

        return $this;
    }

    public function theme(string|Closure $name = 'blackboard'): static
    {
        $this->theme = $name;

        return $this;
    }

    public function getLanguage(): EditorLanguages
    {
        return $this->evaluate($this->language);
    }

    public function getShowLoader(): bool
    {
        return (bool) $this->evaluate($this->showLoader);
    }

    public function getFontSize(): string
    {
        return $this->evaluate($this->fontSize);
    }

    public function getLineNumbersMinChars(): int
    {
        return (int) $this->evaluate($this->lineNumbersMinChars);
    }

    public function getAutomaticLayout(): bool
    {
        return (bool) $this->evaluate($this->automaticLayout);
    }
}
