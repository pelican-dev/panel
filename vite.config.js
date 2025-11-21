import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import { globSync } from 'glob';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                ...globSync('resources/css/**/*.css'),
                ...globSync('resources/js/**/*.js'),

                ...globSync('plugins/*/resources/css/**/*.css'),
                ...globSync('plugins/*/resources/js/**/*.js'),
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
