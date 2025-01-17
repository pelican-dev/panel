<div wire:poll.1s="pullFromSession" id="alert-banner-container" class="flex flex-col gap-4">
    @foreach ($alertBanners as $alertBanner)
        @include('livewire.alerts.alert-banner', ['alertBanner' => $alertBanner])
    @endforeach
</div>
