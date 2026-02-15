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
        $this->pullFromSession();
    }

    #[On('alertBannerSent')]
    public function pullFromSession(): void
    {
        foreach (session()->pull('alert-banners', []) as $alertBanner) {
            $alertBanner = AlertBanner::fromArray($alertBanner);
            $this->alertBanners->put($alertBanner->getId(), $alertBanner);
        }
    }

    /**
     * @param  array{id: string, title: ?string, body: ?string, status: ?string, icon: ?string, closeable: bool}  $alert
     */
    #[On('showAlertBanner')]
    public function showAlertBanner(array $alert): void
    {
        $alertBanner = AlertBanner::fromArray($alert);
        $this->alertBanners->put($alertBanner->getId(), $alertBanner);
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
