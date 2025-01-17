<div class="relative flex min-h-screen shrink-0 justify-center md:px-12 lg:px-0">
    <div
        class="relative z-10 flex flex-1 flex-col bg-white px-4 py-10 shadow-2xl sm:justify-center md:flex-none md:px-28 dark:text-white">
        <main class="mx-auto w-full max-w-md sm:px-4 md:w-96 md:max-w-sm md:px-0">
            <div class="flex">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="sr-only">{{ config('app.name') }}</span>
                    <span class="text-3xl font-bold xs:text-2xl">{{ config('app.name') }}</span>
                </a>
            </div>
            <h2 class="mt-20 text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Reset Password') }}
            </h2>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-8">

                @csrf
                {{ $this->form }}

                <x-filament::button type="submit" class="w-full mt-4" color="primary">
                    {{ __('Reset Password') }}
                </x-filament::button>
            </form>

        </main>
    </div>
    <div class="hidden sm:contents lg:relative lg:block lg:flex-1">
        <img class="absolute inset-0 h-full w-full object-cover" src="/img/background-auth.jpg" alt=""
            unoptimized />
    </div>
</div>
