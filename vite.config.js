import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/*.css',
                'resources/js/*.js',
            ],
            refresh: [...refreshPaths, 'app/Livewire/**'],
        }),
    ],
});
