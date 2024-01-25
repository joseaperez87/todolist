import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import path from 'path';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/sass/app.sass', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            //vue: 'vue/dist/vue.esm-bundler.js',
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '@': '/resources/js',
        },
    },
    server: {
        host: '0.0.0.0',
        port: 3009,
        hmr: {host: 'localhost'},
        watch: {usePolling: true}
    },

});
