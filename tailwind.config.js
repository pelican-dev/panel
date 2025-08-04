import preset from './vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './app/Livewire/**/*.php',

        './resources/views/filament/**/*.blade.php',
        './resources/views/livewire/**/*.blade.php',

        './vendor/filament/**/*.blade.php',
    ],
};
