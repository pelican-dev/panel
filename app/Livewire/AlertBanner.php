<?php

namespace App\Livewire;

use Closure;
use Filament\Notifications\Concerns;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Str;
use Livewire\Wireable;

final class AlertBanner implements Wireable
{
    use Concerns\HasBody;
    use Concerns\HasIcon;
    use Concerns\HasId;
    use Concerns\HasStatus;
    use Concerns\HasTitle;
    use EvaluatesClosures;

    protected bool|Closure $closable = false;

    public static function make(?string $id = null): AlertBanner
    {
        $static = new self();
        $static->id($id ?? Str::orderedUuid());

        return $static;
    }

    /**
     * @return array{id: string, title: ?string, body: ?string, status: ?string, icon: ?string, closeable: bool}
     */
    public function toLivewire(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'body' => $this->getBody(),
            'status' => $this->getStatus(),
            'icon' => $this->getIcon(),
            'closeable' => $this->isCloseable(),
        ];
    }

    public static function fromLivewire(mixed $value): AlertBanner
    {
        $static = AlertBanner::make($value['id']);

        $static->title($value['title']);
        $static->body($value['body']);
        $static->status($value['status']);
        $static->icon($value['icon']);
        $static->closable($value['closeable']);

        return $static;
    }

    public function closable(bool|Closure $closable = true): AlertBanner
    {
        $this->closable = $closable;

        return $this;
    }

    public function isCloseable(): bool
    {
        return $this->evaluate($this->closable);
    }

    public function send(): AlertBanner
    {
        session()->push('alert-banners', $this->toLivewire());

        return $this;
    }

    public function getColorClasses(): string
    {
        return match ($this->getStatus()) {
            'success' => 'text-success-600 dark:text-success-500',
            'warning' => 'text-warning-600 dark:text-warning-500',
            'danger' => 'text-danger-600 dark:text-danger-500',
            default => 'text-info-600 dark:text-info-500',
        };
    }
}
