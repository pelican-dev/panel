<div
    x-data
    x-init="document.addEventListener('livewire:navigated', () => $wire.pullFromSession())"
    id="alert-banner-container"
    class="flex flex-col gap-4"
>
    @foreach ($alertBanners as $alertBanner)
        {{ $alertBanner }}
    @endforeach
</div>
