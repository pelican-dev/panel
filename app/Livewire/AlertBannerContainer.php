<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;

class AlertBannerContainer extends Component
{
    public array $alertBanners;

    public function mount(): void
    {
        $this->alertBanners = [];
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
