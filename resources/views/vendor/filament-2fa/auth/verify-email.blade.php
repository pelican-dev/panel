<div class="filament-fortify-verify-email-page">
    <form method="POST" action="{{ route('verification.send') }}" class="space-y-8">

        @csrf
        <x-filament::button type="submit" class="w-full">
            {{ __('Verify') }}
        </x-filament::button>
    </form>
</div>
