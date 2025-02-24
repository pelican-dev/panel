<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class AlertBannerContainer extends Component
{
    public array $alertBanners;

    public function mount(): void
    {
        $this->alertBanners = [];
        if (config('panel.alert_banner.enabled')) {
            AlertBanner::make()
                ->status(config('panel.alert_banner.status'))
                ->title(config('panel.alert_banner.title'))
                ->body(config('panel.alert_banner.message'))
                ->icon(config('panel.alert_banner.icon'))
                ->closable(config('panel.alert_banner.closeable'))
                ->send();
        }
        $this->pullFromSession();
    }

    public function pullFromSession(): void
    {
        foreach (session()->pull('alert-banners', []) as $alertBanner) {
            $alertBanner = AlertBanner::fromLivewire($alertBanner);
            $this->alertBanners[$alertBanner->getId()] = $alertBanner;
        }
    }

    public function remove(string $id): void
    {
        unset($this->alertBanners[$id]);
    }

    public function render(): View
    {
        return view('livewire.alerts.alert-banner-container');
    }
}
