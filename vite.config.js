import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            content: ['./vendor/vormkracht10/filament-2fa/resources/**.*.blade.php'],
            refresh: [...refreshPaths, 'app/Livewire/**'],
        }),
    ],
});
