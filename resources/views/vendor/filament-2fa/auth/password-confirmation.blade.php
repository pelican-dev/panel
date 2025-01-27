<div @class([
    'flex items-center justify-center min-h-screen bg-gray-100 text-gray-900 filament-fortify-confirm-password-page',
    'dark:bg-gray-900 dark:text-white' => config('filament.dark_mode'),
])>
    <div class="p-2 max-w-md space-y-8 w-screen md:mt-0 md:px-2">
        <form method="POST" action="{{ route('password.confirm') }}" @class([
            'bg-white space-y-8 shadow border border-gray-300 rounded-2xl p-8',
            'dark:bg-gray-800 dark:border-gray-700' => config('filament.dark_mode'),
        ])>

            <h2 class="font-bold tracking-tight text-center text-2xl">
                {{ __('Confirm') }}
            </h2>

            @csrf
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                {{ __('Confirm') }}
            </x-filament::button>
        </form>
    </div>
</div>
