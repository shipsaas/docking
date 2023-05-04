import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),

        laravel({
            input: ['resources/css/console.css', 'resources/js/console/index.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            external: [
                '/assets/img/logo.png',
            ],
        },
    }
});
