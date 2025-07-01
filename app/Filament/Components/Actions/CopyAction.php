<?php

namespace App\Filament\Components\Actions;

use Closure;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;

class CopyAction extends Action
{
    protected Closure|string|null $copyable = null;

    public static function getDefaultName(): ?string
    {
        return 'copy';
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->icon('tabler-clipboard-copy');

        $this->successNotificationTitle(trans('filament::components/copyable.messages.copied'));

        $this->extraAttributes(fn () => [
            'x-on:click' => new HtmlString('window.navigator.clipboard.writeText('.$this->getCopyable().'); $tooltip('.Js::from($this->getSuccessNotificationTitle()).');'),
        ]);
    }

    public function copyable(Closure|string|null $copyable): self
    {
        $this->copyable = $copyable;

        return $this;
    }

    public function getCopyable(): ?string
    {
        return Js::from($this->evaluate($this->copyable));
    }
}
