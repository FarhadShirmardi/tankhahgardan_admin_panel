import laravel from 'laravel-vite-plugin'
import {defineConfig} from 'vite'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/dashboard/style.scss',
                'resources/js/app.js',
            ]
        }),
    ],
});
