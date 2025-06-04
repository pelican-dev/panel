<?php

namespace App\Livewire;

use Closure;
use Filament\Notifications\Concerns;
use Filament\Support\Components\ViewComponent;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

final class AlertBanner extends ViewComponent implements Arrayable
{
    use Concerns\HasBody;
    use Concerns\HasIcon;
    use Concerns\HasId;
    use Concerns\HasStatus;
    use Concerns\HasTitle;
    use EvaluatesClosures;

    protected bool|Closure $closable = false;

    protected string $view = 'livewire.alerts.alert-banner';

    protected string $viewIdentifier = 'notification';

    public function __construct(string $id)
    {
        $this->id($id);
    }

    public static function make(?string $id = null): static
    {
        $static = new self($id ?? Str::orderedUuid());
        $static->configure();

        return $static;
    }

    /**
     * @return array<string, mixed>
     */
    public function getViewData(): array
    {
        return $this->viewData;
    }

    /**
     * @return array{id: string, title: ?string, body: ?string, status: ?string, icon: ?string, closeable: bool}
     */
    public function toArray(): array
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

    /**
     * @param  array{id: string, title: ?string, body: ?string, status: ?string, icon: ?string, closeable: bool}  $data
     */
    public static function fromArray(array $data): AlertBanner
    {
        $static = AlertBanner::make($data['id']);

        $static->title($data['title']);
        $static->body($data['body']);
        $static->status($data['status']);
        $static->icon($data['icon']);
        $static->closable($data['closeable']);

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
        session()->push('alert-banners', $this->toArray());

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
