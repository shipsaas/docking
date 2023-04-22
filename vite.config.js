import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),

        laravel({
            input: ['resources/css/console.css'],
            refresh: true,
        }),

        laravel({
            input: ['resources/js/console/index.js'],
            refresh: true,
        }),
    ],
});
