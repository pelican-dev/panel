import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/css/console.css',
                'resources/js/console.js',
            ],
            refresh: [...refreshPaths, 'app/Livewire/**'],
        }),
    ],
});
