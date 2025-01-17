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
        $this->pullFromSession();
    }

    public function pullFromSession(): void
    {
        $this->alertBanners = array_merge($this->alertBanners, session()->pull('alert-banners', []));
    }

    public function render(): View
    {
        return view('livewire.alerts.alert-banner-container');
    }
}
