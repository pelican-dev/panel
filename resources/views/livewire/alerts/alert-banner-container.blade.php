<div wire:poll.visible.15s="pullFromSession" id="alert-banner-container" class="flex flex-col gap-4">
    @foreach ($alertBanners as $alertBanner)
        {{ $alertBanner }}
    @endforeach
</div>
