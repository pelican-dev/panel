@php
    $alertBanners = session()->get('alert-banners', []);
@endphp

@if (isset($alertBanners))
    <div class="flex flex-col gap-4 p-3 mt-3 mb-3">
        @foreach ($alertBanners as $alertBanner)
            @include('filament.alerts.alert-banner', ['alertBanner' => $alertBanner])
        @endforeach
    </div>
@endif
