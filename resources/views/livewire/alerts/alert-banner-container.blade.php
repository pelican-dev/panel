<div wire:poll.1s="pullFromSession" id="alert-banner-container" class="flex flex-col gap-4">
    @foreach (array_values($alertBanners) as $alertBanner)
        @include('livewire.alerts.alert-banner', ['alertBanner' => $alertBanner])
    @endforeach
</div>
