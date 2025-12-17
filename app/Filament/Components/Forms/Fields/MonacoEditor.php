<?php

namespace App\Filament\Components\Forms\Fields;

use Closure;
use Exception;
use Filament\Forms\Components\Field;

class MonacoEditor extends Field
{
    public bool|Closure $showLoader = true;

    public bool|Closure $automaticLayout = true;

    public int|Closure $lineNumbersMinChars = 3;

    public string|Closure $fontSize = '15px';

    public string|Closure $language = 'html';

    public bool|Closure $enablePreview = true;

    public bool|Closure $showFullScreenToggle = true;

    public string|Closure $theme = 'blackboard';

    protected string $view = 'filament.components.monaco-editor';

    protected function setUp(): void
    {
        $this->showLoader = config('monaco-editor.general.show-loader');
        $this->fontSize = config('monaco-editor.general.font-size');
        $this->lineNumbersMinChars = config('monaco-editor.general.line-numbers-min-chars');
        $this->automaticLayout = config('monaco-editor.general.automatic-layout');
        $this->theme = config('monaco-editor.general.default-theme');
        $this->enablePreview = config('monaco-editor.general.enable-preview');
        $this->showFullScreenToggle = config('monaco-editor.general.show-full-screen-toggle');
    }

    /*
     *  Default theme for the editor, change theme from config.
     */
    public function editorTheme()
    {
        if (!isset(config('monaco-editor.themes')[$this->theme])) {
            throw new Exception("Theme {$this->theme} not found in config file.");
        }

        return json_encode([
            'base' => config("monaco-editor.themes.{$this->theme}.base"),
            'inherit' => config("monaco-editor.themes.{$this->theme}.inherit"),
            'rules' => config("monaco-editor.themes.{$this->theme}.rules"),
            'colors' => config("monaco-editor.themes.{$this->theme}.colors"),
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * @return $this
     *
     * Set the language for the editor: html|javascript|css|php|vue|...
     */
    public function language(string|Closure $lang = 'html'): static
    {
        if ($lang === 'blade' || $lang === 'blade.php') {
            // Since Monaco does not ship with a Blade language, we rewrite it to HTML to get at least some highlighting.
            $lang = 'html';
        }

        $this->language = $lang;

        return $this;
    }

    /**
     * @param  bool|Closure  $show
     * @return $this
     *
     * Show/Hide loader when editor is loading.
     */
    public function showLoader(bool|Closure $condition = true): static
    {
        $this->showLoader = $condition;

        return $this;
    }

    /**
     * @return $this
     *
     * Hide the loader when editor is loading.
     */
    public function hideLoader(): static
    {
        $this->showLoader = false;

        return $this;
    }

    /**
     * @return $this
     *
     * Change the font size of the editor's content.
     */
    public function fontSize(string|Closure $size = '15px'): static
    {
        $this->fontSize = $size;

        return $this;
    }

    /**
     * @return $this
     *
     * Change the line numbers min characters
     */
    public function lineNumbersMinChars(int|Closure $value = 3): static
    {
        $this->lineNumbersMinChars = $value;

        return $this;
    }

    /**
     * @param  bool|Closure  $value
     * @return $this
     *
     * Enable/Disable automatic layout.
     */
    public function automaticLayout(bool|Closure $condition = true): static
    {
        $this->automaticLayout = $condition;

        return $this;
    }

    public function showFullScreenToggle(bool|Closure $condition = true): static
    {
        $this->showFullScreenToggle = $condition;

        return $this;
    }

    public function hideFullScreenButton()
    {
        $this->showFullScreenToggle = false;

        return $this;
    }

    public function theme(string|Closure $name = 'blackboard'): static
    {
        $this->theme = $name;

        return $this;
    }

    // -----------------------

    public function getLanguage()
    {
        return $this->evaluate($this->language);
    }

    public function getShowLoader()
    {
        return (bool) $this->evaluate($this->showLoader);
    }

    public function getFontSize()
    {
        return $this->evaluate($this->fontSize);
    }

    public function getLineNumbersMinChars()
    {
        return (int) $this->evaluate($this->lineNumbersMinChars);
    }

    public function getAutomaticLayout()
    {
        return (bool) $this->evaluate($this->automaticLayout);
    }

    public function getShowFullScreenToggle()
    {
        return (bool) $this->evaluate($this->showFullScreenToggle);
    }
}
