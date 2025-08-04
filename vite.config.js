import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import { globSync } from 'glob';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                ...globSync('resources/css/**/*.css'),
                ...globSync('resources/js/**/*.js'),
            ],
            refresh: [...refreshPaths, 'app/Livewire/**'],
        }),
    ],
});
