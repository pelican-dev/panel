<?php

namespace App\Livewire;

use Filament\Notifications\Concerns;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

final class AlertBanner implements Arrayable
{
    use Concerns\HasBody;
    use Concerns\HasIcon;
    use Concerns\HasId;
    use Concerns\HasStatus;
    use Concerns\HasTitle;
    use EvaluatesClosures;

    public static function make(?string $id = null): static
    {
        $static = new self();
        $static->id($id ?? Str::orderedUuid());

        return $static;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'body' => $this->getBody(),
            'status' => $this->getStatus(),
            'icon' => $this->getIcon(),
        ];
    }

    public static function fromArray(array $data): static
    {
        $static = static::make();

        $static->id($data['id']);
        $static->title($data['title']);
        $static->body($data['body']);
        $static->status($data['status']);
        $static->icon($data['icon']);

        return $static;
    }

    public function send(): static
    {
        session()->push('alert-banners', $this->toArray());

        return $this;
    }
}
