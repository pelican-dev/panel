<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class AlertBannerContainer extends Component
{
    /** @var array<AlertBanner> */
    public array $alertBanners;

    public function mount(): void
    {
        $this->alertBanners = [];
        $this->pullFromSession();
    }

    #[On('alertBannerSent')]
    public function pullFromSession(): void
    {
        foreach (session()->pull('alert-banners', []) as $alertBanner) {
            $alertBanner = AlertBanner::fromLivewire($alertBanner);
            $this->alertBanners[$alertBanner->getId()] = $alertBanner;
        }
    }

    public function remove(string $id): void
    {
        $alertBanners = &$this->alertBanners;
        unset($alertBanners[$id]);
    }

    public function render(): View
    {
        return view('livewire.alerts.alert-banner-container');
    }
}
