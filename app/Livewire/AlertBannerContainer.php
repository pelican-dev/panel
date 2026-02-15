<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class AlertBannerContainer extends Component
{
    public AlertBannerCollection $alertBanners;

    public function mount(): void
    {
        $this->alertBanners = new AlertBannerCollection();

        foreach (session()->pull('alert-banners', []) as $alertBanner) {
            // Alerts created during Livewire requests should have been consumed by the event handler on the same page.
            if (!empty($alertBanner['from_livewire'])) {
                // If they weren't, then discard them instead of showing on the wrong page.
                continue;
            }

            $alertBanner = AlertBanner::fromArray($alertBanner);
            $this->alertBanners->put($alertBanner->getId(), $alertBanner);
        }
    }

    #[On('alertBannerSent')]
    public function pullFromSession(): void
    {
        foreach (session()->pull('alert-banners', []) as $alertBanner) {
            unset($alertBanner['from_livewire']);
            $alertBanner = AlertBanner::fromArray($alertBanner);
            $this->alertBanners->put($alertBanner->getId(), $alertBanner);
        }
    }

    public function remove(string $id): void
    {
        if ($this->alertBanners->has($id)) {
            $this->alertBanners->forget($id);
        }
    }

    public function render(): View
    {
        return view('livewire.alerts.alert-banner-container');
    }
}
