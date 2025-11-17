import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import commonjs from '@originjs/vite-plugin-commonjs';

export default defineConfig({
     server: {
        host: true,
        port: 5173,
        strictPort: true,
        hmr: {
            host: "localhost",
        },
        watch: {
            usePolling: true,
        },
    },
    plugins: [
        laravel({
            input: [ 'resources/css/app.scss','resources/js/app.js'],
            refresh: true,
        }),
        vue(),
         commonjs.default
    ],
});
