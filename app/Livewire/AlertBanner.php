<?php

namespace App\Livewire;

use Filament\Notifications\Concerns;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\Support\Arrayable;

class AlertBanner implements Arrayable
{
    use Concerns\HasBody;
    use Concerns\HasIcon;
    use Concerns\HasStatus;
    use Concerns\HasTitle;
    use EvaluatesClosures;

    public static function make(): static
    {
        // @phpstan-ignore-next-line
        return app(static::class);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->getTitle(),
            'body' => $this->getBody(),
            'status' => $this->getStatus(),
            'icon' => $this->getIcon(),
        ];
    }

    public function send(): static
    {
        $alerts = session()->get('alert-banners', []);
        $alerts[] = $this->toArray();

        session()->flash('alert-banners', $alerts);

        return $this;
    }
}
